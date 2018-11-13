<?php

namespace BBDO\Cms\Http\Controllers\Admin;

use BBDO\Cms\Domain;
use File;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class FilesController extends BaseController
{
    protected $layout = 'admin.template';
    protected $service = null;
    protected $module_type = 'FILESMOD';
    protected $languages;
    protected $default_lang;

    public function __construct()
    {
        $this->module_type = 'FILES';

        $this->default_lang = config('cms.default_locale');
        $this->languages = config('app.locales');

        view()->share('modules', config('cms.modules'));
        view()->share('user', "");
        view()->share('module_type', $this->module_type);
        view()->share('module_title', config('cms.' . $this->module_type . '.description'));

        $this->service = new Domain\File();
    }

    public function filterRequests($route, $request)
    {
    }

    public function getManager($manager_type, $module_type = null, $input_type = null, $input_id = null, $value = null)
    {
        $this->prepareManager($manager_type, $module_type, $input_type, $input_id, $value);

        $this->data['maxFileSize'] = config('cms.files.' . $manager_type . '.maxFileSize');
        $this->data['acceptedFiles'] = config('cms.files.' . $manager_type . '.acceptedFiles');

        if ($module_type != null) {
            $this->data['mode'] = 'popup';
            return bbdoview('admin.files.manager', $this->data);
        }

        $this->data['mode'] = 'manager';

        return bbdoview('admin.files.manager-page', $this->data);
    }

    private function prepareManager($manager_type, $module_type, $input_type, $input_id, $value)
    {
        // if content module
        // use general Content module
        if (in_array($module_type, config('cms.content_modules'))) {
            $module_type = 'CONTENT';
        }

        // todo filter images on input type when != all
        $items = $this->service->getAllAdmin(0, $manager_type, $module_type);

        $itemService = new Domain\Item("");

        $content_types = config('cms.files.' . $manager_type . '.content_type');
        $content_links = $itemService->getContentsearchIds($content_types);

        $categories = config('cms.modules');

        $this->data['categories'] = $categories;
        $this->data['files'] = $items;
        $this->data['manager_type'] = $manager_type;
        $this->data['module_type'] = $module_type;
        $this->data['input_type'] = $input_type;
        $this->data['input_id'] = $input_id;
        $this->data['value'] = $value;
        $this->data['content_links'] = $content_links;
        $this->data['mode'] = $value;
        $this->data['image_config'] = $this->service->getTypeConfig($input_type);
    }

    public function getPopupManager($manager_type, $module_type, $input_type, $input_id, $value = null)
    {
        $this->prepareManager($manager_type, $module_type, $input_type, $input_id, $value);

        $this->data['mode'] = 'popup';
        $this->data['maxFileSize'] = config('cms.files.' . $manager_type . '.maxFileSize');
        $this->data['acceptedFiles'] = config('cms.files.' . $manager_type . '.acceptedFiles');

        return bbdoview('admin.files.popup_manager', $this->data);
    }

    public function getFiles($manager_type, $mode = null, $input_id = null, $module_type = null, $input_type = null)
    {
        $this->prepareManager($manager_type, $module_type, $input_type, $input_id, null);

        $this->data['mode'] = $mode;

        return bbdoview('admin.partials.filelist', $this->data);
    }

    public function postUpload(Request $request, $manager_type, $module_type = null, $input_type = null)
    {
        // if content module
        // use general Content module
        if ($module_type != null && in_array($module_type, config('cms.content_modules'))) {
            $module_type = 'CONTENT';
        }

        $file = $request->file('file');

        if ($file != null) {
            $extension = $file->getClientOriginalExtension();

            $dir = public_path('/uploads/' . $manager_type . '/');
            $filename = str_replace('.' . $extension, "", $file->getClientOriginalName()) . date("ymdHis") . ".$extension";

            $upload_success = $file->move($dir, $filename);

            // thumbnails
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png'];
            $contentType = mime_content_type($dir . $filename);

            // checks if is an image
            if (in_array($contentType, $allowedMimeTypes)) {
                $config = $this->service->getTypeConfig($input_type);

                // resize original
                $imageSize = getimagesize($dir . $filename);
                $imageWidth = $imageSize[0];

                if (
                    $config != null &&
                    $config['optimize_original'] &&
                    $imageWidth != $config['width']
                ) {
                    $image = Image::make($dir . $filename);
                    $image->resize($config['width'], $config['height'], function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    // save resized
                    $image->save($dir . $filename);
                }

                if ($config != null) {
                    $image = Image::make($dir . $filename);

                    if ($config['generate_thumb']) {
                        $image->resize($config['thumb_width'], $config['thumb_height'], function ($constraint) {
                            $constraint->aspectRatio();
                        });

                        // create the directory if it doesn't exist
                        if (!File::exists($dir . "thumbs/")) {
                            File::makeDirectory($dir . "thumbs/", 0775, true);
                        }

                        // save the thumbnail
                        $image->save($dir . "thumbs/" . $filename);
                    }
                }
            }

            $data = array(
                'file' => $filename,
                'type' => $manager_type,
                'description' => '',
                'editor_id' => 1,
                'module' => $module_type,
            );

            $this->service->create($data);

            if ($upload_success) {
                return response()->json('success', 200);
            } else {
                return response()->json('error', 400);
            }
        }

        return response()->json('error no file', 400);
    }

    public function postAssignCategory(Request $request)
    {
        $id = $request->input('id');
        $module = $request->input('module');
        $status = $request->input('status');

        if ($id != null && $module != null && $status != null) {
            $data = array(
                'id' => $id,
                'module' => $module,
                'status' => $status,
            );

            $this->service->assignModule($data);

            return response()->json('success', 200);
        } else {
            return response()->json('error', 400);
        }
    }

    public function postRemove(Request $request)
    {
        $id = $request->input('id');

        if ($id != null) {
            $data = array(
                'id' => $id,
            );

            $this->service->garbage($data);

            return response()->json('success', 200);
        } else {
            return response()->json('error', 400);
        }
    }

    public function postPurge($manager_type)
    {
        $files = $this->service->getAllAdmin(1, $manager_type);

        if (count($files) > 0) {
            foreach ($files as $file) {
                $path = public_path('/uploads/' . $manager_type . '/');
                File::delete($path . $file->file);
            }

            $ids = $files->pluck('id');

            $data = array(
                'ids' => $ids,
            );

            $this->service->purge($data);

            return response()->json('success', 200);
        } else {
            return response()->json('error', 400);
        }
    }

    public function getImageContainer($id, $type)
    {
        return $this->service->getImageContainer($id, $type);
    }
}
