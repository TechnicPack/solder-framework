export default class ErrorCollection {
    /**
     * Construct a new ErrorCollection instance.
     */
    constructor() {
        this.errors = {};
    }

    /**
     * Determine if the collection has any errors.
     */
    hasErrors() {
        return ! _.isEmpty(this.errors);
    };


    /**
     * Determine if the collection has errors for a given field.
     */
    has(field) {
        return _.indexOf(_.keys(this.errors), field) > -1;
    };


    /**
     * Get all of the raw errors for the collection.
     */
    all() {
        return this.errors;
    };


    /**
     * Get all of the errors for the collection in a flat array.
     */
    flatten() {
        return _.flatten(_.toArray(this.errors));
    };


    /**
     * Get the first error message for a given field.
     */
    get(field) {
        if (this.has(field)) {
            return this.errors[field][0];
        }
    };


    /**
     * Set the raw errors for the collection.
     */
    set(errors) {
        if (typeof errors === 'object') {
            this.errors = errors;
        } else {
            this.errors = {'form': ['Something went wrong. Please try again or contact customer support.']};
        }
    };


    /**
     * Remove errors from the collection.
     */
    forget(field) {
        if (typeof field === 'undefined') {
            this.errors = {};
        } else {
            Vue.delete(this.errors, field);
        }
    };
}