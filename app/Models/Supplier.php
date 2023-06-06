<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Models\Purchase;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Supplier extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'suppliers';
    protected $guarded = ['id'];
    protected $fillable = [
        'supplier_code',
        'supplier_name',
        'supplier_phone',
        'supplier_email',
        'contact_name',
        'branch_id',
        'address',
        'supplier_profile',
        'created_by',
        'updated_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function updateBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    public function purchass()
    {
        return $this->hasMany(Purchase::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function khAddress()
    {
        return $this->belongsTo(Address::class, 'supplier_address', '_code');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    // #######
    // Get User
    // ####
    public function getCreatedBysAttribute()
    {
        return optional($this->createBy)->name ?? "";
    }
    public function getUpdatedBysAttribute()
    {
        return optional($this->updateBy)->name ?? "";
    }
    
    public function getTotalPurchasedAttribute()
    {
        return optional($this->purchass)->count();
    }
    public function getAmountAttribute()
    {
        return Helper::formatCurrency(optional($this->purchass)->sum('amount_payable'), '$');
    }
    public function getAmountPaidAttribute()
    {
        return Helper::formatCurrency(optional($this->purchass)->sum('received_amount'), '$');
    }
    public function getAmountDueAttribute()
    {
        return Helper::formatCurrency(optional($this->purchass)->sum('due_amount'), '$');
    }

    public function setSupplierProfileAttribute($value)
    {
        $attribute_name = "supplier_profile";
        // or use your own disk, defined in config/filesystems.php
        $disk = config('backpack.base.root_disk_name');
        // destination path relative to the disk above
        $destination_path = "public/uploads/suppliers";

        // if the image was erased
        if ($value == null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (Str::startsWith($value, 'data:image')) {
            // 0. Make the image
            $image = \Image::make($value)->encode('jpg', 90);

            // 1. Generate a filename.
            $filename = md5($value . time()) . '.jpg';

            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path . '/' . $filename, $image->stream());

            // 3. Delete the previous image, if there was one.
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // 4. Save the public path to the database
            // but first, remove "public/" from the path, since we're pointing to it
            // from the root folder; that way, what gets saved in the db
            // is the public URL (everything that comes after the domain name)
            $public_destination_path = Str::replaceFirst('public/', '', $destination_path);
            $this->attributes[$attribute_name] = $public_destination_path . '/' . $filename;
        }
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
