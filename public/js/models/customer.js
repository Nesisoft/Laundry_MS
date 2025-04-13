/**
 * Customer model for offline data handling
 */
class Customer {
    constructor(data = {}) {
        this.id = data.id || null;
        this.first_name = data.first_name || '';
        this.last_name = data.last_name || '';
        this.phone_number = data.phone_number || '';
        this.email = data.email || '';
        this.sex = data.sex || 'male';
        this.archived = data.archived || false;
        this.added_by = data.added_by || null;
        this.created_at = data.created_at || new Date().toISOString();
        this.updated_at = data.updated_at || new Date().toISOString();

        // Address properties
        this.address = data.address || null;
    }

    get fullName() {
        return `${this.first_name} ${this.last_name}`;
    }

    get formattedAddress() {
        if (!this.address) return 'No address provided';

        const parts = [];
        if (this.address.street) parts.push(this.address.street);
        if (this.address.city) parts.push(this.address.city);
        if (this.address.state) parts.push(this.address.state);
        if (this.address.zip_code) parts.push(this.address.zip_code);
        if (this.address.country) parts.push(this.address.country);

        return parts.join(', ') || 'No address provided';
    }

    toJSON() {
        return {
            id: this.id,
            first_name: this.first_name,
            last_name: this.last_name,
            phone_number: this.phone_number,
            email: this.email,
            sex: this.sex,
            archived: this.archived,
            added_by: this.added_by,
            created_at: this.created_at,
            updated_at: this.updated_at,
            address: this.address
        };
    }
}

/**
 * Address model for offline data handling
 */
class Address {
    constructor(data = {}) {
        this.id = data.id || null;
        this.street = data.street || '';
        this.city = data.city || '';
        this.state = data.state || '';
        this.zip_code = data.zip_code || '';
        this.country = data.country || '';
        this.latitude = data.latitude || null;
        this.longitude = data.longitude || null;
        this.created_at = data.created_at || new Date().toISOString();
        this.updated_at = data.updated_at || new Date().toISOString();
    }

    toJSON() {
        return {
            id: this.id,
            street: this.street,
            city: this.city,
            state: this.state,
            zip_code: this.zip_code,
            country: this.country,
            latitude: this.latitude,
            longitude: this.longitude,
            created_at: this.created_at,
            updated_at: this.updated_at
        };
    }
}
