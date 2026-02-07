/**
 * Type definitions and constants
 */

// User roles
export const USER_ROLES = {
    ADMIN: 'admin',
    USER: 'user',
    MODERATOR: 'moderator'
}

// API endpoints
export const API_ENDPOINTS = {
    USERS: '/api/users',
    POSTS: '/api/posts',
    COMMENTS: '/api/comments'
}

// Form validation rules
export const VALIDATION_RULES = {
    EMAIL: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
    PHONE: /^(\+420)?[0-9]{9}$/,
    PASSWORD_MIN_LENGTH: 8
}

// Status constants
export const STATUS = {
    ACTIVE: 'active',
    INACTIVE: 'inactive',
    PENDING: 'pending',
    DELETED: 'deleted'
}

// Theme constants
export const THEMES = {
    LIGHT: 'light',
    DARK: 'dark',
    AUTO: 'auto'
}
