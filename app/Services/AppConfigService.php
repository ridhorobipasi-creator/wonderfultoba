<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;

/**
 * Reads and writes the small set of application config an admin may change
 * from the panel (see config/editable.php).
 *
 * Values live in the `settings` table, not in .env. That is deliberate: if the
 * web process could write .env, any file-upload or path-traversal bug would
 * become full server takeover. Storing them in the database keeps .env a
 * deploy-time artifact that the running application only ever reads.
 */
class AppConfigService
{
    /** settings.key row these values live under. */
    public const STORAGE_KEY = 'app_config';

    /**
     * Field definitions, with anything on the denied list stripped out.
     *
     * The denylist is enforced here rather than trusted to the config file so
     * that adding a credential to 'fields' by mistake still cannot expose it.
     *
     * @return array
     */
    public static function fields()
    {
        $denied = array_map('strtoupper', config('editable.denied', []));

        return collect(config('editable.fields', []))
            ->reject(function ($field, $key) use ($denied) {
                $isDenied = in_array(strtoupper($key), $denied, true)
                    || in_array(strtoupper(str_replace('.', '_', $field['config'] ?? '')), $denied, true);

                if ($isDenied) {
                    Log::warning("AppConfigService: refusing to expose denied config key '{$key}'.");
                }

                return $isDenied;
            })
            ->all();
    }

    /**
     * Stored overrides, keyed by field name.
     *
     * @return array
     */
    public static function stored()
    {
        $value = Setting::where('key', self::STORAGE_KEY)->value('value');

        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        return is_array($value) ? $value : [];
    }

    /**
     * Current effective value of each field — the stored override if there is
     * one, otherwise whatever .env/config already resolves to.
     *
     * @return array
     */
    public static function current()
    {
        $stored = self::stored();
        $current = [];

        foreach (self::fields() as $key => $field) {
            $current[$key] = $stored[$key] ?? config($field['config']);
        }

        return $current;
    }

    /**
     * Apply stored overrides on top of config(). Called at boot.
     *
     * @param  array|null  $stored  pre-loaded values, to avoid a second query
     * @return void
     */
    public static function apply($stored = null)
    {
        $stored = is_array($stored) ? $stored : self::stored();

        if (! $stored) {
            return;
        }

        $overrides = [];

        foreach (self::fields() as $key => $field) {
            if (array_key_exists($key, $stored)) {
                $overrides[$field['config']] = $stored[$key];
            }
        }

        if ($overrides) {
            config($overrides);
        }
    }

    /**
     * Persist a set of values, keeping only known, permitted fields.
     *
     * Input is filtered against the field list rather than mass-assigned, so a
     * crafted form post cannot introduce a key that was never offered.
     *
     * @param  array  $input
     * @return array  the values actually stored
     */
    public static function save(array $input)
    {
        $fields = self::fields();
        $clean = self::stored();

        foreach ($fields as $key => $field) {
            if (! array_key_exists($key, $input)) {
                continue;
            }

            $value = $input[$key];

            if (($field['type'] ?? 'text') === 'boolean') {
                $value = (bool) $value;
            } elseif (($field['type'] ?? 'text') === 'number') {
                $value = (int) $value;
            }

            $clean[$key] = $value;
        }

        // Drop anything no longer offered, so removing a field from the config
        // file also retires whatever was stored for it.
        $clean = array_intersect_key($clean, $fields);

        Setting::updateOrCreate(
            ['key' => self::STORAGE_KEY],
            ['value' => $clean]
        );

        return $clean;
    }

    /**
     * Validation rules for the fields currently on offer.
     *
     * @return array
     */
    public static function rules()
    {
        $rules = [];

        foreach (self::fields() as $key => $field) {
            $rules[$key] = $field['rules'] ?? 'nullable|string';
        }

        return $rules;
    }
}
