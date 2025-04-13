/**
 * Item model
 */
class Item {
    constructor(data = {}) {
        this.id = data.id || null;
        this.name = data.name || '';
        this.amount = data.amount || 0;
        this.image = data.image || null;
        this.category = data.category || null;
        this.added_by = data.added_by || null;
        this.archived = data.archived || false;
        this.created_at = data.created_at || new Date().toISOString();
        this.updated_at = data.updated_at || new Date().toISOString();
    }

    get formattedAmount() {
        return `₦${parseFloat(this.amount).toFixed(2)}`;
    }

    get imageUrl() {
        if (!this.image) return null;
        return `/storage/${this.image}`;
    }

    get status() {
        return this.archived ? 'archived' : 'active';
    }

    toJSON() {
        return {
            id: this.id,
            name: this.name,
            amount: this.amount,
            image: this.image,
            category: this.category,
            added_by: this.added_by,
            archived: this.archived,
            created_at: this.created_at,
            updated_at: this.updated_at
        };
    }
}
