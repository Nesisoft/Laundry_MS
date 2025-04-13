/**
 * Discount model
 */
class Discount {
    constructor(data = {}) {
        this.id = data.id || null;
        this.name = data.name || '';
        this.type = data.type || 'percentage'; // percentage or amount
        this.value = data.value || 0;
        this.description = data.description || '';
        this.expiration_date = data.expiration_date || null;
        this.added_by = data.added_by || null;
        this.archived = data.archived || false;
        this.created_at = data.created_at || new Date().toISOString();
        this.updated_at = data.updated_at || new Date().toISOString();
    }

    get formattedValue() {
        if (this.type === 'percentage') {
            return `${this.value}%`;
        } else {
            return `₦${this.value.toFixed(2)}`;
        }
    }

    get isExpired() {
        if (!this.expiration_date) return false;

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const expiryDate = new Date(this.expiration_date);
        expiryDate.setHours(0, 0, 0, 0);

        return expiryDate < today;
    }

    get status() {
        if (this.archived) return 'archived';
        if (this.isExpired) return 'expired';
        return 'active';
    }

    toJSON() {
        return {
            id: this.id,
            name: this.name,
            type: this.type,
            value: this.value,
            description: this.description,
            expiration_date: this.expiration_date,
            added_by: this.added_by,
            archived: this.archived,
            created_at: this.created_at,
            updated_at: this.updated_at
        };
    }
}
