<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BackpackUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=BackpackUserSeeder
     * @return void
     */
    public function run()
    {
        $developer = Role::firstOrCreate(['name' => 'DEVELOPER'])->id;
        $administrator = Role::firstOrCreate(['name' => 'ADMINISTRATOR'])->id;
        $manager = Role::firstOrCreate(['name' => 'MANAGER'])->id;
        $assistant_manager = Role::firstOrCreate(['name' => 'ASSISTANT MANAGER'])->id;
        $purchase_manager = Role::firstOrCreate(['name' => 'PURCHASE MANAGER'])->id;
        $saler = Role::firstOrCreate(['name' => 'SALER'])->id;

        Permission::firstOrCreate(['name' => 'list dashboards'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // CUSTOMERS
        Permission::firstOrCreate(['name' => 'list customers'])->roles()->sync([
            $developer, $administrator, $manager, $assistant_manager, $saler, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create customers'])->roles()->sync([
            $developer, $administrator, $manager, $assistant_manager, $saler, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show customers'])->roles()->sync([
            $developer, $administrator, $manager, $assistant_manager, $saler, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update customers'])->roles()->sync([
            $developer, $administrator, $manager, $assistant_manager, $saler, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete customers'])->roles()->sync([
            $developer, $administrator, $manager, $assistant_manager, $saler, $purchase_manager
        ]);

        // PRODUCTS
        Permission::firstOrCreate(['name' => 'list products'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create products'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show products'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update products'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete products'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // PUSHASES
        Permission::firstOrCreate(['name' => 'list purchase'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create purchase'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show purchase'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update purchase'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete purchase'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // SUPPLIERS
        Permission::firstOrCreate(['name' => 'list suppliers'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create suppliers'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show suppliers'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update suppliers'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete suppliers'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);


        // INVOICES
        Permission::firstOrCreate(['name' => 'list invoices'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create invoices'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show invoices'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update invoices'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete invoices'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // INVOICE RETURN
        Permission::firstOrCreate(['name' => 'list invocie return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create invocie return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show invocie return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update invocie return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete invocie return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // PURCHASE RETURN
        Permission::firstOrCreate(['name' => 'list purchase return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create purchase return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show purchase return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update purchase return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete purchase return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // ADJUSTMENT
        Permission::firstOrCreate(['name' => 'list adjustment'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create adjustment'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show adjustment'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update adjustment'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete adjustment'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);


        // EXPENSES
        Permission::firstOrCreate(['name' => 'list expenses'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create expenses'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show expenses'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update expenses'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete expenses'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // QUOTATION
        Permission::firstOrCreate(['name' => 'list quotation'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create quotation'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show quotation'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update quotation'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete quotation'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // ITEM LOCATIONS
        Permission::firstOrCreate(['name' => 'list item location'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create item location'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show item location'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update item location'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete item location'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // ITEM BRANCHES
        Permission::firstOrCreate(['name' => 'list branches'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create branches'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show branches'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update branches'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete branches'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // ITEM ATTRIBUTES
        Permission::firstOrCreate(['name' => 'list attributes'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create attributes'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show attributes'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update attributes'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete attributes'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // ITEM CATEGORIES
        Permission::firstOrCreate(['name' => 'list categories'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create categories'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show categories'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update categories'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete categories'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // ITEM PRODUCT UNIT
        Permission::firstOrCreate(['name' => 'list product unit'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create product unit'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show product unit'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update product unit'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete product unit'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // ITEM PURCHASE RETURN
        Permission::firstOrCreate(['name' => 'list purchase return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create purchase return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show purchase return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update purchase return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete purchase return'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // USERS
        Permission::firstOrCreate(['name' => 'list users'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create users'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show users'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update users'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete users'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        // AUTHENTICATION
        Permission::firstOrCreate(['name' => 'list authentications'])->roles()->sync([
            $developer, $administrator, $manager
        ]);
        Permission::firstOrCreate(['name' => 'list roles'])->roles()->sync([
            $developer, $administrator
        ]);
        Permission::firstOrCreate(['name' => 'create roles'])->roles()->sync([
            $developer
        ]);
        Permission::firstOrCreate(['name' => 'update roles'])->roles()->sync([
            $developer
        ]);
        Permission::firstOrCreate(['name' => 'list permissions'])->roles()->sync([
            $developer, $administrator
        ]);
        Permission::firstOrCreate(['name' => 'create permissions'])->roles()->sync([
            $developer
        ]);
        Permission::firstOrCreate(['name' => 'update permissions'])->roles()->sync([
            $developer
        ]);

        // STOCKS
        Permission::firstOrCreate(['name' => 'list stocks'])->roles()->sync([
            $developer
        ]);
        Permission::firstOrCreate(['name' => 'show stocks'])->roles()->sync([
            $developer
        ]);
        Permission::firstOrCreate(['name' => 'update stocks'])->roles()->sync([
            $developer
        ]);
        Permission::firstOrCreate(['name' => 'create stocks'])->roles()->sync([
            $developer
        ]);
        Permission::firstOrCreate(['name' => 'delete stocks'])->roles()->sync([
            $developer
        ]);


        // OPTION
        Permission::firstOrCreate(['name' => 'list customer group'])->roles()->sync([
            $developer
        ]);
        Permission::firstOrCreate(['name' => 'show customer group'])->roles()->sync([
            $developer
        ]);
        Permission::firstOrCreate(['name' => 'update customer group'])->roles()->sync([
            $developer
        ]);
        Permission::firstOrCreate(['name' => 'create customer group'])->roles()->sync([
            $developer
        ]);
        Permission::firstOrCreate(['name' => 'delete customer group'])->roles()->sync([
            $developer
        ]);

        // REPORTS
        Permission::firstOrCreate(['name' => 'list customer report'])->roles()->sync([
            $developer, $administrator
        ]);

        // BRAND
        Permission::firstOrCreate(['name' => 'list brand'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create brand'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show brand'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update brand'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete brand'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        // PAYMENT
        Permission::firstOrCreate(['name' => 'list payment'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'create payment'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'show payment'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'update payment'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);
        Permission::firstOrCreate(['name' => 'delete payment'])->roles()->sync([
            $developer, $administrator, $manager, $purchase_manager
        ]);

        $user = User::updateOrCreate([
            'email' => 'dev@gmail.com',
        ], [
            'name' => 'POS',
            'phone' => '+8555555555',
            'password' => \Hash::make('12345678')
        ]);
        $user->roles()->syncWithoutDetaching([$developer, $administrator, $manager, $assistant_manager, $saler, $purchase_manager]);
    }
}
