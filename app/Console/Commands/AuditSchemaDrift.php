<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AuditSchemaDrift extends Command
{
    protected $signature = 'audit:schema-drift';
    protected $description = 'Audit models for schema drift (columns expected by models but missing in database)';

    public function handle()
    {
        $this->info("Starting Schema Drift Audit...");
        
        $modelsPath = app_path('Models');
        if (!File::exists($modelsPath)) {
            $this->error("Models directory not found.");
            return;
        }

        $files = File::allFiles($modelsPath);
        $driftFound = false;

        foreach ($files as $file) {
            $className = 'App\\Models\\' . str_replace('/', '\\', $file->getRelativePathname());
            $className = str_replace('.php', '', $className);

            if (!class_exists($className)) {
                continue;
            }

            $reflection = new \ReflectionClass($className);
            if (!$reflection->isInstantiable() || !$reflection->isSubclassOf(\Illuminate\Database\Eloquent\Model::class)) {
                continue;
            }

            try {
                $model = new $className();
                $table = $model->getTable();
                
                if (!Schema::hasTable($table)) {
                    $this->warn("Table '{$table}' for model {$className} does not exist in the database!");
                    $driftFound = true;
                    continue;
                }

                $columns = Schema::getColumnListing($table);
                $fillable = $model->getFillable();
                $casts = array_keys($model->getCasts());

                // Properties expected by model
                $expectedColumns = array_unique(array_merge($fillable, $casts));
                
                // Filter out standard non-column casts or accessors, though typically casts map to columns.
                // Also ignore generic casts that aren't columns (like custom accessors) if possible, but let's list them first.
                
                $missingColumns = [];
                foreach ($expectedColumns as $col) {
                    // Ignore some common virtual casts or relationship counts if they accidentally got here
                    if (in_array($col, ['id', 'created_at', 'updated_at', 'deleted_at'])) continue;
                    
                    if (!in_array($col, $columns)) {
                        // Check if it's an accessor (has get[X]Attribute or new Attribute return type)
                        $studlyCol = Str::studly($col);
                        if ($reflection->hasMethod('get' . $studlyCol . 'Attribute') || $reflection->hasMethod($col)) {
                            // Likely an accessor or relation
                            continue;
                        }
                        $missingColumns[] = $col;
                    }
                }

                if (!empty($missingColumns)) {
                    $this->error("Drift detected in {$className} (Table: {$table})");
                    $this->line("Expected columns missing from DB: " . implode(', ', $missingColumns));
                    $driftFound = true;
                }

            } catch (\Exception $e) {
                $this->error("Could not process model {$className}: " . $e->getMessage());
            }
        }

        if (!$driftFound) {
            $this->info("No schema drift detected between Model fillable/casts and database tables!");
        } else {
            $this->warn("Schema drift audit completed. Please review the errors above.");
        }
    }
}
