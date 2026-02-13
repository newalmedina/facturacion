<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -------------------
        // USERS
        // -------------------
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'users_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
                if (!Schema::hasColumn('users', 'super_admin')) {
                    $table->boolean('super_admin')->default(false)->after('center_id');
                }
            });
            echo "Table users updated successfully!\n";
        } else {
            echo "Table users does not exist, skipping...\n";
        }

        // -------------------
        // APPOINTMENTS
        // -------------------
        if (Schema::hasTable('appointments')) {
            Schema::table('appointments', function (Blueprint $table) {
                if (!Schema::hasColumn('appointments', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'appointments_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
            });
            echo "Table appointments updated successfully!\n";
        } else {
            echo "Table appointments does not exist, skipping...\n";
        }

        // -------------------
        // appointment_items
        // -------------------
        if (Schema::hasTable('appointment_items')) {
            Schema::table('appointment_items', function (Blueprint $table) {
                if (!Schema::hasColumn('appointment_items', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'appointment_items_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
            });
            echo "Table appointment_items updated successfully!\n";
        } else {
            echo "Table appointment_items does not exist, skipping...\n";
        }

        // -------------------
        // APPOINTMENT_TEMPLATES
        // -------------------
        if (Schema::hasTable('appointment_templates')) {
            Schema::table('appointment_templates', function (Blueprint $table) {
                if (!Schema::hasColumn('appointment_templates', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'appointment_templates_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
            });
            echo "Table appointment_templates updated successfully!\n";
        } else {
            echo "Table appointment_templates does not exist, skipping...\n";
        }

        // -------------------
        // APPOINTMENT_TEMPLATE_SLOTS
        // -------------------
        if (Schema::hasTable('appointment_template_slots')) {
            Schema::table('appointment_template_slots', function (Blueprint $table) {
                if (!Schema::hasColumn('appointment_template_slots', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'appointment_template_slots_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
            });
            echo "Table appointment_template_slots updated successfully!\n";
        } else {
            echo "Table appointment_template_slots does not exist, skipping...\n";
        }

        // -------------------
        // BRANDS
        // -------------------
        if (Schema::hasTable('brands')) {
            Schema::table('brands', function (Blueprint $table) {
                if (!Schema::hasColumn('brands', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'brands_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
            });
            echo "Table brands updated successfully!\n";
        } else {
            echo "Table brands does not exist, skipping...\n";
        }

        // -------------------
        // CATEGORIES
        // -------------------
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (!Schema::hasColumn('categories', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'categories_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
            });
            echo "Table categories updated successfully!\n";
        } else {
            echo "Table categories does not exist, skipping...\n";
        }

        // -------------------
        // CMS_CONTENTS
        // -------------------
        if (Schema::hasTable('cms_contents')) {
            Schema::table('cms_contents', function (Blueprint $table) {
                if (!Schema::hasColumn('cms_contents', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'cms_contents_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
            });
            echo "Table cms_contents updated successfully!\n";
        } else {
            echo "Table cms_contents does not exist, skipping...\n";
        }

        // -------------------
        // CMS_CONTENT_IMAGES
        // -------------------
        if (Schema::hasTable('cms_content_images')) {
            Schema::table('cms_content_images', function (Blueprint $table) {
                if (!Schema::hasColumn('cms_content_images', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'cms_content_images_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
            });
            echo "Table cms_content_images updated successfully!\n";
        } else {
            echo "Table cms_content_images does not exist, skipping...\n";
        }

        // -------------------
        // CUSTOMERS
        // -------------------
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                if (!Schema::hasColumn('customers', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'customers_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
            });
            echo "Table customers updated successfully!\n";
        } else {
            echo "Table customers does not exist, skipping...\n";
        }

        // -------------------
        // ITEMS
        // -------------------
        if (Schema::hasTable('items')) {
            Schema::table('items', function (Blueprint $table) {
                if (!Schema::hasColumn('items', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'items_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
            });
            echo "Table items updated successfully!\n";
        } else {
            echo "Table items does not exist, skipping...\n";
        }

        // -------------------
        // ORDERS
        // -------------------
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'orders_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
            });
            echo "Table orders updated successfully!\n";
        } else {
            echo "Table orders does not exist, skipping...\n";
        }

        // -------------------
        // OTHER_EXPENSES
        // -------------------
        if (Schema::hasTable('other_expenses')) {
            Schema::table('other_expenses', function (Blueprint $table) {
                if (!Schema::hasColumn('other_expenses', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'other_expenses_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
            });
            echo "Table other_expenses updated successfully!\n";
        } else {
            echo "Table other_expenses does not exist, skipping...\n";
        }

        // -------------------
        // OTHER_EXPENSE_ITEMS
        // -------------------
        if (Schema::hasTable('other_expense_items')) {
            Schema::table('other_expense_items', function (Blueprint $table) {
                if (!Schema::hasColumn('other_expense_items', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'other_expense_items_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
            });
            echo "Table other_expense_items updated successfully!\n";
        } else {
            echo "Table other_expense_items does not exist, skipping...\n";
        }

        // -------------------
        // SUPPLIERS
        // -------------------
        if (Schema::hasTable('suppliers')) {
            Schema::table('suppliers', function (Blueprint $table) {
                if (!Schema::hasColumn('suppliers', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('id');
                    $table->foreign('center_id', 'suppliers_center_id_fk')
                        ->references('id')->on('centers')
                        ->onDelete('set null');
                }
            });
            echo "Table suppliers updated successfully!\n";
        } else {
            echo "Table suppliers does not exist, skipping...\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // -------------------
        // USERS
        // -------------------
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'center_id')) {
                    $table->dropForeign('users_center_id_fk');
                    $table->dropColumn('center_id');
                }
                if (Schema::hasColumn('users', 'super_admin')) {
                    $table->dropColumn('super_admin');
                }
            });
            echo "Table users rolled back successfully!\n";
        }

        // -------------------
        // APPOINTMENTS
        // -------------------
        if (Schema::hasTable('appointments')) {
            Schema::table('appointments', function (Blueprint $table) {
                if (Schema::hasColumn('appointments', 'center_id')) {
                    $table->dropForeign('appointments_center_id_fk');
                    $table->dropColumn('center_id');
                }
            });
            echo "Table appointments rolled back successfully!\n";
        }

        // -------------------
        // appointment_items
        // -------------------
        if (Schema::hasTable('appointment_items')) {
            Schema::table('appointment_items', function (Blueprint $table) {
                if (Schema::hasColumn('appointment_items', 'center_id')) {
                    $table->dropForeign('appointment_items_center_id_fk');
                    $table->dropColumn('center_id');
                }
            });
            echo "Table appointment_items rolled back successfully!\n";
        }

        // -------------------
        // APPOINTMENT_TEMPLATES
        // -------------------
        if (Schema::hasTable('appointment_templates')) {
            Schema::table('appointment_templates', function (Blueprint $table) {
                if (Schema::hasColumn('appointment_templates', 'center_id')) {
                    $table->dropForeign('appointment_templates_center_id_fk');
                    $table->dropColumn('center_id');
                }
            });
            echo "Table appointment_templates rolled back successfully!\n";
        }

        // -------------------
        // APPOINTMENT_TEMPLATE_SLOTS
        // -------------------
        if (Schema::hasTable('appointment_template_slots')) {
            Schema::table('appointment_template_slots', function (Blueprint $table) {
                if (Schema::hasColumn('appointment_template_slots', 'center_id')) {
                    $table->dropForeign('appointment_template_slots_center_id_fk');
                    $table->dropColumn('center_id');
                }
            });
            echo "Table appointment_template_slots rolled back successfully!\n";
        }

        // -------------------
        // BRANDS
        // -------------------
        if (Schema::hasTable('brands')) {
            Schema::table('brands', function (Blueprint $table) {
                if (Schema::hasColumn('brands', 'center_id')) {
                    $table->dropForeign('brands_center_id_fk');
                    $table->dropColumn('center_id');
                }
            });
            echo "Table brands rolled back successfully!\n";
        }

        // -------------------
        // CATEGORIES
        // -------------------
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (Schema::hasColumn('categories', 'center_id')) {
                    $table->dropForeign('categories_center_id_fk');
                    $table->dropColumn('center_id');
                }
            });
            echo "Table categories rolled back successfully!\n";
        }

        // -------------------
        // CMS_CONTENTS
        // -------------------
        if (Schema::hasTable('cms_contents')) {
            Schema::table('cms_contents', function (Blueprint $table) {
                if (Schema::hasColumn('cms_contents', 'center_id')) {
                    $table->dropForeign('cms_contents_center_id_fk');
                    $table->dropColumn('center_id');
                }
            });
            echo "Table cms_contents rolled back successfully!\n";
        }

        // -------------------
        // CMS_CONTENT_IMAGES
        // -------------------
        if (Schema::hasTable('cms_content_images')) {
            Schema::table('cms_content_images', function (Blueprint $table) {
                if (Schema::hasColumn('cms_content_images', 'center_id')) {
                    $table->dropForeign('cms_content_images_center_id_fk');
                    $table->dropColumn('center_id');
                }
            });
            echo "Table cms_content_images rolled back successfully!\n";
        }

        // -------------------
        // CUSTOMERS
        // -------------------
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                if (Schema::hasColumn('customers', 'center_id')) {
                    $table->dropForeign('customers_center_id_fk');
                    $table->dropColumn('center_id');
                }
            });
            echo "Table customers rolled back successfully!\n";
        }

        // -------------------
        // ITEMS
        // -------------------
        if (Schema::hasTable('items')) {
            Schema::table('items', function (Blueprint $table) {
                if (Schema::hasColumn('items', 'center_id')) {
                    $table->dropForeign('items_center_id_fk');
                    $table->dropColumn('center_id');
                }
            });
            echo "Table items rolled back successfully!\n";
        }

        // -------------------
        // ORDERS
        // -------------------
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'center_id')) {
                    $table->dropForeign('orders_center_id_fk');
                    $table->dropColumn('center_id');
                }
            });
            echo "Table orders rolled back successfully!\n";
        }

        // -------------------
        // OTHER_EXPENSES
        // -------------------
        if (Schema::hasTable('other_expenses')) {
            Schema::table('other_expenses', function (Blueprint $table) {
                if (Schema::hasColumn('other_expenses', 'center_id')) {
                    $table->dropForeign('other_expenses_center_id_fk');
                    $table->dropColumn('center_id');
                }
            });
            echo "Table other_expenses rolled back successfully!\n";
        }

        // -------------------
        // OTHER_EXPENSE_ITEMS
        // -------------------
        if (Schema::hasTable('other_expense_items')) {
            Schema::table('other_expense_items', function (Blueprint $table) {
                if (Schema::hasColumn('other_expense_items', 'center_id')) {
                    $table->dropForeign('other_expense_items_center_id_fk');
                    $table->dropColumn('center_id');
                }
            });
            echo "Table other_expense_items rolled back successfully!\n";
        }

        // -------------------
        // SUPPLIERS
        // -------------------
        if (Schema::hasTable('suppliers')) {
            Schema::table('suppliers', function (Blueprint $table) {
                if (Schema::hasColumn('suppliers', 'center_id')) {
                    $table->dropForeign('suppliers_center_id_fk');
                    $table->dropColumn('center_id');
                }
            });
            echo "Table suppliers rolled back successfully!\n";
        }
    }
};
