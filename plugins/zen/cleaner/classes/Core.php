<?php namespace Zen\Cleaner\Classes;

use Illuminate\Filesystem\Filesystem;
use DB;
use Input;

class Core
{
    public function clean()
    {
        $steep = Input::get('steep');
        $fs = new Filesystem;
        $uploads_path = storage_path().'/app/uploads/public';

        if(!file_exists($uploads_path)) {
            $this->json([
                'over' => true
            ]);
            return;
        }

        $directory_list = $fs->directories($uploads_path);
        $deleted_size = 0;
        $deleted_count = 0;

        if($steep > count($directory_list)) {
            $this->json([
                'over' => true
            ]);
            return;
        }

        $dir = $directory_list[$steep - 1];

        $file_list = $this->getFileList($dir);

        if(!count($file_list)) {
            $this->delDir($dir);
        }

        foreach ($file_list as $file_item) {
            $file_path = $file_item['path'];
            $file_size = $file_item['size'];
            $file_name = basename($file_path);
            $test = DB::table('system_files')->where('disk_name', $file_name)->first();
            if(!$test) {
                unlink($file_path);
                $deleted_size += $file_size;
                $deleted_count++;
            } else {
                if(!$test->attachment_type) continue;
                if(!class_exists($test->attachment_type)) continue;
                $model_item = app($test->attachment_type)->find($test->attachment_id);

                if(!$model_item) {
                    unlink($file_path);
                    DB::table('system_files')->where('id', $test->id)->delete();
                    $deleted_size += $file_size;
                    $deleted_count++;
                }
            }
        }

        $this->json([
            'deleted_size' => $deleted_size,
            'deleted_count' => $deleted_count,
            'over' => false
        ]);

    }

    public function json($array)
    {
        echo json_encode ($array, JSON_UNESCAPED_UNICODE);
    }

    public function getFileList($dir)
    {
        $fs = new Filesystem;
        $files = $fs->allFiles($dir, 1);
        $file_list = [];
        foreach ($files as $file) {
            $file_path = $file->getRelativePathname();
            $full_file_path = $dir.'/'.$file_path;
            $file_list[] = [
                'path' => $full_file_path,
                'size' => filesize($full_file_path),
            ];
        }
        return $file_list;
    }

    public function delDir($folder) {
        if (is_dir($folder)) {
            $handle = opendir($folder);
            while ($subfile = readdir($handle)) {
                if ($subfile == '.' or $subfile == '..') continue;
                if (is_file($subfile)) unlink("{$folder}/{$subfile}");
                else $this->delDir("{$folder}/{$subfile}");
            }
            closedir($handle);
            rmdir($folder);
        } else
            unlink($folder);
    }

    public function dirCount()
    {
        $fs = new Filesystem;
        $uploads_path = storage_path().'/app/uploads/public';

        if(file_exists($uploads_path)) {
            $directory_list = $fs->directories($uploads_path);
        } else {
            $directory_list = [];
        }

        $this->json([
            'dir_count' => count($directory_list)
        ]);
    }

}