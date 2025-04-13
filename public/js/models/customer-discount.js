/**
 * CustomerDiscount model
 */
class CustomerDiscount {
    constructor(data = {}) {
        this.id = data.id || null;
        this.customer_id = data.customer_id || null;
        this.discount_id = data.discount_id || null;
        this.customer_expiration_date = data.customer_expiration_date || null;
        this.added_by = data.added_by || null;
        this.created_at = data.created_at || new Date().toISOString();
        this.updated_at = data.updated_at || new Date().toISOString();

        // Relationships
        this.customer = data.customer || null;
        this.discount = data.discount || null;
    }

    get isExpired() {
        if (!this.customer_expiration_date) {
            // If customer-specific expiration is not set, check discount expiration
            if (this.discount && this.discount.expiration_date) {
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const expiryDate = new Date(this.discount.expiration_date);
                expiryDate.setHours(0, 0, 0, 0);

                return expiryDate < today;
            }
            return false;
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const expiryDate = new Date(this.customer_expiration_date);
        expiryDate.setHours(0, 0, 0, 0);

        return expiryDate < today;
    }

    get status() {
        if (this.isExpired) return 'expired';
        return 'active';
    }

    get customerName() {
        return this.customer ? `${this.customer.first_name} ${this.customer.last_name}` : 'Unknown Customer';
    }

    get discountName() {
        return this.discount ? this.discount.name || 'Unnamed Discount' : 'Unknown Discount';
    }

    get discountValue() {
        if (!this.discount) return '';

        if (this.discount.type === 'percentage') {
            return `${this.discount.value}%`;
        } else {
            return `₦${this.discount.value.toFixed(2)}`;
        }
    }

    toJSON() {
        return {
            id: this.id,
            customer_id: this.customer_id,
            discount_id: this.discount_id,
            customer_expiration_date: this.customer_expiration_date,
            added_by: this.added_by,
            created_at: this.created_at,
            updated_at: this.updated_at
        };
    }
}
