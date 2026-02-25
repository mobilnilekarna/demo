import { ref, computed, reactive, watch } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import axios from 'axios'
import { useAnalytics } from "@/Composables/useAnalytics.js"
import { useToast } from "@/Composables/useToast.js"

const { trackLoginUser, trackRegisterUser, trackLogoutUser } = useAnalytics();

// Local reactive user state - initialized from page props
const user = ref(null)
/**
 * Composable for authentication state and methods
 */
export function useAuth() {
    const page = usePage()

    // Inicializace user jen když je null (při prvním načtení)
    if (user.value === null) {
        user.value = page.props.auth?.user || null;
    }

    // Sledování změn v page.props.auth?.user pro synchronizaci stavu
    // (např. když se session vyprší nebo když se uživatel odhlásí na jiném zařízení)
    watch(() => page.props.auth?.user, (newUser, oldUser) => {
        const oldLocalUser = user.value;
        if (newUser !== oldLocalUser) {
            user.value = newUser || null;

            // Pokud se uživatel odhlásil (byl přihlášen a teď není) a jsme na chráněné stránce
            if (oldLocalUser && !newUser) {
                const currentPath = window.location.pathname;
                if (currentPath.startsWith('/dashboard') || currentPath.startsWith('/profile')) {
                    router.visit(route('dashboard.login', {}, false));
                }
            }
        }
    }, { immediate: false })

    // Check if user is authenticated
    const isAuthenticated = computed(() => !!user.value)

    // Check if user is guest
    const isGuest = computed(() => !isAuthenticated.value)

    // Check if user has specific role
    const hasRole = (role) => {
        return user.value?.role === role
    }

    // Check if user has specific permission
    const hasPermission = (permission) => {
        return user.value?.permissions?.includes(permission) || false
    }

    // Login method
    const login = async (credentials) => {
        try {
            const response = await axios.post(
                route('auth.login', {}, false),
                {
                    email: credentials.email,
                    password: credentials.password
                }
            )

            // Update user state with response data
            if (response.data.user) {
                user.value = response.data.user;
                trackLoginUser("email");

                // Show success notification
                const toast = useToast();
                toast.success('Přihlášení úspěšné', 'Byli jste úspěšně přihlášeni.');

                return response.data;
            } else {
                throw new Error('Login failed - no user data received');
            }
        } catch (error) {
            // Re-throw the error so it can be handled in the component
            throw error;
        }
    }

    const register = async (credentials) => {
        try {
            const response = await axios.post(
                route('auth.register', {}, false),
                {
                    name: credentials.name,
                    email: credentials.email,
                    password: credentials.password,
                    password_confirmation: credentials.password_confirmation
                }
            )

            // Update user state with response data
            if (response.data.user) {
                user.value = response.data.user;
                trackRegisterUser("email");
                return response.data;
            } else {
                throw new Error('Registration failed - no user data received');
            }
        } catch (error) {
            // Re-throw the error so it can be handled in the component
            throw error;
        }
    }

    // Logout method
    const logout = async () => {
        try {
            await axios.post(route('auth.logout', {}, false));
            // Clear user state
            user.value = null;
            trackLogoutUser();

            // Show success notification
            const toast = useToast();
            toast.success('Odhlášení úspěšné', 'Byli jste úspěšně odhlášeni.');

            // Redirect to login page
            // Check if we're on a protected route, if so redirect to dashboard login
            const currentPath = window.location.pathname;
            if (currentPath.startsWith('/dashboard')) {
                router.visit(route('dashboard.login', {}, false));
            } else {
                // For other routes, redirect to home or login
                router.visit('/');
            }

            return { success: true };
        } catch (error) {
            // Re-throw the error so it can be handled in the component
            throw error;
        }
    }

    return {
        user,
        isAuthenticated,
        isGuest,
        hasRole,
        hasPermission,
        login,
        register,
        logout
    }
}
