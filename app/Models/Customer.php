<?php

namespace App\Models;

use App\Models\Branch;
use Laravel\Passport\HasApiTokens;
use App\Helpers\Helper;
use App\Models\Invoice;
use Illuminate\Support\Str;
use App\Helpers\Address\AddressTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Customer extends Authenticatable
{
    use CrudTrait;
    use SoftDeletes;
    use Notifiable, HasFactory, HasApiTokens;
    use AddressTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'customers';
    protected $primaryKey = 'id';
    // protected $guarded = ['id'];
    protected $fillable = [
        'customer_code',
        'customer_name',
        'customer_last_name',
        'customer_gender',
        'customer_phone',
        'customer_email',
        'customer_password',
        'customer_dob',
        'customer_profile',
        'customer_house_no',
        'customer_street_no',
        'customer_address',
        'branch_id',
        'created_by',
        'updated_by',
        'customer_group',
        'price_group',
        'day_able',
        'company'
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

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function khAddress()
    {
        return $this->belongsTo(Address::class, 'customer_address', '_code');
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

    //#######################
    // Address
    //####
    public function getCityEnAttribute()
    {
        return $this->getAddress('city', 'en', $this->customer_address);
    }
    public function getCustomerCodeNameAttribute()
    {
        return "("+$this->customer_code + ") "+ $this->customer_name;
    }

    public function getDistrictEnAttribute()
    {
        return $this->getAddress('district', 'en', $this->customer_address);
    }

    public function getCommuneEnAttribute()
    {
        return $this->getAddress('commune', 'en', $this->customer_address);
    }

    public function getVillageEnAttribute()
    {
        return $this->getAddress('village', 'en', $this->customer_address);
    }
    public function getTotalBoughtAttribute()
    {
        return optional($this->invoices())->count();
    }
    public function getAmountAttribute()
    {
        return Helper::formatCurrency(optional($this->invoices())->sum('amount_payable'), '$');
    }
    public function getAmountPaidAttribute()
    {
        return Helper::formatCurrency(optional($this->invoices())->sum('received_amount'), '$');
    }
    public function getAmountDueAttribute()
    {
        return Helper::formatCurrency(optional($this->invoices())->sum('due_amount'), '$');
    }


    public function getFullAddressEnAttribute()
    {
        $houseNo = $streetNo = '';
        if (!empty($this->customer_house_no)) {
            $houseNo = 'House ' . $this->customer_house_no . ', ' ?? '';
        }
        if (!empty($this->customer_street_no)) {
            $streetNo = 'Street ' . $this->customer_street_no . ',' ?? '';
        }
        return $houseNo . $streetNo . $this->getAddress('full', 'en', $this->customer_address);
    }

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


    public function setCustomerProfileAttribute($value)
    {
        $attribute_name = "customer_profile";
        // or use your own disk, defined in config/filesystems.php
        $disk = config('backpack.base.root_disk_name');
        // destination path relative to the disk above
        $destination_path = "public/uploads/customers";

        // if the image was erased
        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (Str::startsWith($value, 'data:image'))
        {
            // 0. Make the image
            $image = \Image::make($value)->encode('jpg', 90);

            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';

            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());

            // 3. Delete the previous image, if there was one.
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // 4. Save the public path to the database
            // but first, remove "public/" from the path, since we're pointing to it
            // from the root folder; that way, what gets saved in the db
            // is the public URL (everything that comes after the domain name)
            $public_destination_path = Str::replaceFirst('public/', '', $destination_path);
            $this->attributes[$attribute_name] = $public_destination_path.'/'.$filename;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
