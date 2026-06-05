import re

with open('app/Http/Controllers/Admin/PackageController.php', 'r', encoding='utf-8') as f:
    content = f.read()

duplicate_method = '''
    public function duplicate(Package )
    {
        try {
             = ->replicate();
            ->name = ->name . ' (Copy)';
            ->slug = \Illuminate\Support\Str::slug(->name) . '-' . time();
            ->status = 'inactive'; // Default duplicated packages to inactive
            ->save();

            // Replicate images
            foreach (->packageImages as ) {
                ->packageImages()->create([
                    'image_path' => ->image_path,
                    'sort_order' => ->sort_order,
                ]);
            }

            // Replicate cities
            ->cities()->sync(->cities->pluck('id')->toArray());

            ->logActivity('duplicated', "Duplicated package: {->name} to {->name}", );
            SyncController::triggerSync();

            return redirect()->route('admin.packages.edit', ->id)
                ->with('success', 'Package successfully duplicated! You are now editing the copy.');
        } catch (\Exception ) {
            Log::error('Package Duplication Failed: ' . ->getMessage());
            return back()->with('error', 'Failed to duplicate package. ' . ->getMessage());
        }
    }
'''

# Insert right before the last closing brace
content = re.sub(r'}\s*$', duplicate_method + '\n}\n', content)

with open('app/Http/Controllers/Admin/PackageController.php', 'w', encoding='utf-8') as f:
    f.write(content)
print('Done!')
