/**
 * Användarinformation som behövs i komponenter och på sidor.
 */
import { writable } from 'svelte/store';

export const user = writable({"auth": false, "userdata": null });