<?php

namespace App\Common;

use App\Models\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Attach this Trait to a User (or other model) for easier read/writes on Replies
 *
 * @author Munna Khan
 */
trait Imageable
{
    /**
     * Check if model has an images.
     *
     * @return bool
     */
    public function hasImages()
    {
        return (bool) $this->images()->count();
    }

    /**
     * Return collection of images related to the imageable
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')
            ->where(function ($q) {
                $q->whereNull('featured')->orWhere('featured', 0);
            })->orderBy('order', 'asc');
    }

    /**
     * Return the image related to the imageable
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')
            ->orderBy('order', 'asc');
    }

    /**
     * Get avatar.
     */
    public function avatar(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    /**
     * Return the logo related to the logoable
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function logo(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')
            ->where('featured', '!=', 1);
    }

    /**
     * Get logo by Type logo
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function logoImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')
            ->where('type', 'logo');
    }

    /**
     * Return the featured Image related to the imageable
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function featuredImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')
            ->where('featured', 1);
    }

    /**
     * Return the featured Image related to the imageable
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function featureImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')
            ->where('type', 'feature');
    }

    /**
     * Return the featured Image related to the imageable
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function coverImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')
            ->where('type', 'cover');
    }

    /**
     * Return the Background Image related to the imageable
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function backgroundImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')
            ->where('type', 'background');
    }

    /**
     * Return the slider image for mobile app
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function mobileImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')
            ->where('type', 'mobile');
    }

    /**
     * Save images
     *
     * @param  file  $image
     * @return image model
     */
    public function saveImage($image, $type = null)
    {
        $dir = image_storage_dir();

        // if (!Storage::exists($dir))
        // 	Storage::makeDirectory($dir, 0775, true, true);

        $path = Storage::put($dir, $image);

        $originalName = $image->getClientOriginalName();
        $imageSize = $image->getSize();
        $extension = $image->getClientOriginalExtension();

        return $this->createImage($path, $originalName, $extension, $imageSize, $type);
    }

    /**
     * Update images
     *
     * @param  file  $image
     * @return image model
     */
    public function updateImage($image, $type = null)
    {
        // Delete the old image if exist
        $this->deleteImageTypeOf($type);

        return $this->saveImage($image, $type);
    }

    /**
     * Save images from external URL
     *
     * @param  file  $image
     *
     * @return image model
     */
    public function saveImageFromUrl($url, $type = null)
    {
        // Get file info and validate
        $file_headers = get_headers($url, true);
        $pathinfo = pathinfo($url);
        // $size = getimagesize($url);

        if ($file_headers === false) {
            return;
        } // when server not found

        // Get file extension
        $extension = isset($pathinfo['extension']) ? $pathinfo['extension'] : substr($url, strrpos($url, '.', -1) + 1);

        // Check if the file is a valid image file
        if (!in_array($extension, config('image.mime_types', ['jpg', 'png', 'jpeg']))) {
            return;
        }

        // Get file name
        $name = isset($pathinfo['filename']) ? $pathinfo['filename'] . '.' . $extension : substr($url, strrpos($url, '/', -1) + 1);

        // Get the original file
        $file_content = file_get_contents($url);

        // Get file size in Bite
        $size = isset($file_headers['Content-Length']) ? $file_headers['Content-Length'] : strlen($file_content);

        if (is_array($size)) {
            $size = array_key_exists(1, $size) ? $size[1] : $size[0];
        }

        // Make path and upload
        $path = image_storage_dir() . '/' . uniqid() . '.' . $extension;

        Storage::put($path, $file_content);

        return $this->createImage($path, $name, $extension, $size, $type);
    }

    /**
     * Deletes the given image.
     *
     * @return bool
     */
    public function deleteImage($image = null)
    {
        if (!$image) {
            $image = $this->image;
        }

        if (optional($image)->path) {
            Storage::delete($image->path);

            Storage::deleteDirectory(image_cache_path($image->path));

            return $image->delete();
        }
    }

    /**
     * Deletes the Featured Image of this model.
     *
     * @return bool
     */
    public function deleteFeaturedImage()
    {
        if ($img = $this->featuredImage) {
            $this->deleteImage($img);
        }
    }

    /**
     * Deletes the Featured Image of this model.
     *
     * @return bool
     */
    public function deleteCoverImage()
    {
        if ($img = $this->coverImage) {
            $this->deleteImage($img);
        }
    }

    /**
     * Deletes the special type of image of this model.
     *
     * @return bool
     */
    public function deleteImageTypeOf($type)
    {
        if ($type) {
            // Delete the old image if exist
            $rel = $type . 'Image';

            if ($img = $this->$rel) {
                $this->deleteImage($img);
            }
        }
    }

    /**
     * Deletes the Brand Logo Image of this model.
     *
     * @return bool
     */
    public function deleteLogo()
    {
        // Will be removed
        if ($img = $this->logo) {
            $this->deleteImage($img);
        }

        if ($img = $this->logoImage) {
            $this->deleteImage($img);
        }
    }

    /**
     * Deletes all the images of this model.
     *
     * @return bool
     */
    public function flushImages()
    {
        foreach ($this->images as $image) {
            $this->deleteImage($image);
        }

        $this->deleteLogo();

        $this->deleteFeaturedImage();
    }

    /**
     * Create image model
     *
     * @return array
     */
    private function createImage($path, $name, $ext = '.jpeg', $size = null, $type = null)
    {
        return $this->image()->create([
            'path' => $path,
            'name' => $name,
            'type' => $type,
            'extension' => $ext,
            // 'featured' => ($featured == 1 || $featured == 2) ? (int)$featured : (int)filter_var($featured, FILTER_VALIDATE_BOOLEAN ),
            'size' => $size,
        ]);
    }

    /**
     * Prepare the previews for the dropzone
     *
     * @return array
     */
    public function previewImages()
    {
        $urls = '';
        $configs = '';

        foreach ($this->images as $image) {
            // $path = Storage::url($image->path);
            $path = url('image/' . $image->path);
            $deleteUrl = route('image.delete', $image->id);
            $urls .= '"' . $path . '",';
            $configs .= '{caption:"' . $image->name . '", size:' . $image->size . ', url: "' . $deleteUrl . '", key:' . $image->id . '},';
        }

        return [
            'urls' => rtrim($urls, ','),
            'configs' => rtrim($configs, ','),
        ];
    }
}
