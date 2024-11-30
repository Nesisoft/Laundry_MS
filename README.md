# Laundry management app for settings

I want to build a generalized pickup and delivery SaaS system for laundry service businesses. 
Business Onboarding:
First, we have to physical meet with a business and upon agreement, the business will be registered on our system by entering the business details. Then the system will generate an access_token for the business and send it to the business email provided.
The business will then have to enter the access_token and upon successfully verification, the business will now have access to complete the registration process by providing other relevant details, and also add/create a manager account.
Manager:
The manager can now:
•	Manage services
•	Manage vehicles
•	Manage drivers
•	Manage customers
•	Manage promotional codes (Also including giving them out to customers)
•	Manage pickup requests (including changing statuses of requests, and assigning request to drivers)
•	Manage delivery requests (including changing statuses of requests, and assigning request to drivers)
•	Manage pickup requests payments
•	Manage delivery requests payments
•	Manage orders
•	Manage invoices
•	Manage invoice payments
•	Follow driver delivery routes
•	Follow driver pickup routes
•	View and respond to customer service ratings
•	Send email and SMS notifications to customers


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->string('country')->nullable();
            $table->decimal('latitude', 9, 6)->nullable();
            $table->decimal('longitude', 9, 6)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null')->onUpdate('cascade');
            $table->string('access_token', 255);
            $table->string('name', 255);
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('banner', 255)->nullable();
            $table->string('motto', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('type', ['admin', 'manager', 'user'])->default('manager');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('number', 255)->unique();
            $table->enum('type', ['car', 'motorcycle'])->default('car');
            $table->string('model', 255)->nullable();
            $table->string('year', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade')->onUpdate('cascade');
            $table->string('email', 255)->nullable();
            $table->string('phone_number', 20);
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('full_name', 255)->nullable();
            $table->enum('sex', ['male', 'female'])->default('male');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade')->onUpdate('cascade');
            $table->string('email', 255)->nullable();
            $table->string('phone_number', 20);
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('full_name', 255)->nullable();
            $table->enum('sex', ['male', 'female'])->default('male');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};


return new class extends Migration
{
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('discount_type', ['percentage', 'amount'])->default('percentage');
            $table->decimal('discount', 10, 2);
            $table->string('description')->nullable();
            $table->date('expiration_date')->nullable();
            $table->timestamps();
            $table->index('updated_at', 'discounts_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
    }
};


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained('discounts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('customer_discounts');
    }
};


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickup_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('location')->nullable();
            $table->decimal('latitude', 9, 6)->nullable();
            $table->decimal('longitude', 9, 6)->nullable();
            $table->date('date');
            $table->time('time');
            $table->decimal('amount', 10, 2);
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'accepted', 'in-progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('pickup_requests');
    }
};


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickup_request_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('pickup_requests')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['Cash', 'MoMo', 'Card']);
            $table->enum('status', ['paid', 'unpaid']);
            $table->timestamps();

            // Indexes
            $table->index('amount');
            $table->index('method');
            $table->index('status');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_request_payments');
    }
};


return new class extends Migration
{
    public function up()
    {
        Schema::create('pickup_request_driver_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('request_id')->constrained('pickup_requests')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('status', ['in-progress', 'completed', 'cancelled'])->default('in-progress');
            $table->timestamps();

            // Indexes
            $table->index('status', 'pickup_request_driver_assignments_status_idx1');
            $table->index('created_at', 'pickup_request_driver_assignments_created_at_idx1');
            $table->index('updated_at', 'pickup_request_driver_assignments_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pickup_request_driver_assignments');
    }
};


return new class extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->string('image')->nullable();  // New image column
            $table->timestamps();

            // Indexes
            $table->index('name', 'items_name_idx1');
            $table->index('image', 'items_image_idx1');
            $table->index('created_at', 'items_created_at_idx1');
            $table->index('updated_at', 'items_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();

            // Indexes
            $table->index('name', 'order_statuses_name_idx1');
            $table->index('created_at', 'order_statuses_created_at_idx1');
            $table->index('updated_at', 'order_statuses_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_statuses');
    }
};

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('order_status_id')->constrained('order_statuses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('status');
            $table->timestamps();

            // Indexes
            $table->index('status', 'orders_status_idx1');
            $table->index('created_at', 'orders_created_at_idx1');
            $table->index('updated_at', 'orders_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 10, 2);
            $table->integer('quantity');
            $table->decimal('total_amount', 10, 2)->storedAs('amount * quantity');
            $table->timestamps();

            // Indexes
            $table->index('amount', 'order_items_amount_idx1');
            $table->index('quantity', 'order_items_quantity_idx1');
            $table->index('total_amount', 'order_items_total_amount_idx1');
            $table->index('created_at', 'order_items_created_at_idx1');
            $table->index('updated_at', 'order_items_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->storedAs('`amount` * `discount`');
            $table->decimal('actual_amount', 10, 2)->storedAs('`amount` - `discount_amount`');
            $table->enum('status', ['fully paid', 'partly paid', 'unpaid'])->default('unpaid');
            $table->boolean('smsed')->default(false);
            $table->timestamps();

            // Indexes
            $table->index('amount', 'invoices_amount_idx1');
            $table->index('discount_amount', 'invoices_discount_amount_idx1');
            $table->index('discount', 'invoices_discount_idx1');
            $table->index('actual_amount', 'invoices_actual_amount_idx1');
            $table->index('status', 'invoices_status_idx1');
            $table->index('created_at', 'invoices_created_at_idx1');
            $table->index('updated_at', 'invoices_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['Cash', 'MoMo', 'Card']);
            $table->enum('status', ['fully paid', 'partly paid']);
            $table->timestamps();

            // Indexes
            $table->index('amount', 'invoice_payments_amount_idx1');
            $table->index('method', 'invoice_payments_method_idx1');
            $table->index('status', 'invoice_payments_status_idx1');
            $table->index('created_at', 'invoice_payments_created_at_idx1');
            $table->index('updated_at', 'invoice_payments_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_payments');
    }
};

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('location', 255);
            $table->decimal('latitude', 9, 6);
            $table->decimal('longitude', 9, 6);
            $table->date('date');
            $table->time('time');
            $table->decimal('amount', 10, 2);
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'accepted', 'in-progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            // Indexes
            $table->index('status', 'delivery_requests_status_idx1');
            $table->index('created_at', 'delivery_requests_created_at_idx1');
            $table->index('updated_at', 'delivery_requests_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_requests');
    }
};

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_request_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['Cash', 'MoMo', 'Card']);
            $table->enum('status', ['paid', 'unpaid']);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('request_id')->references('id')->on('delivery_requests')->onDelete('cascade')->onUpdate('cascade');

            // Indexes
            $table->index('amount', 'delivery_request_payments_amount_idx1');
            $table->index('method', 'delivery_request_payments_method_idx1');
            $table->index('status', 'delivery_request_payments_status_idx1');
            $table->index('created_at', 'delivery_request_payments_created_at_idx1');
            $table->index('updated_at', 'delivery_request_payments_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_request_payments');
    }
};

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_requests_driver_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('request_id')->constrained('delivery_requests')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('status', ['in-progress', 'completed', 'cancelled'])->default('in-progress');
            $table->timestamps();

            // Indexes
            $table->index('status', 'delivery_requests_assignments_status_idx1');
            $table->index('created_at', 'delivery_requests_assignments_created_at_idx1');
            $table->index('updated_at', 'delivery_requests_assignments_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_requests_driver_assignments');
    }
};

return new class extends Migration
{
    public function up()
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['pickup', 'delivery', 'payment', 'promotion', 'rating', 'general']);
            $table->enum('medium', ['sms', 'email'])->default('sms');
            $table->string('title', 255);
            $table->text('message');
            $table->timestamps();

            $table->index('type');
            $table->index('title');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_templates');
    }
};

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('rating');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_ratings');
    }
};
