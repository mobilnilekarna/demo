
import { trans } from '../i18n.js';

export function validateEmail(email) {
    if (!email) {
        return false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        return false;
    }
    return true;
}
