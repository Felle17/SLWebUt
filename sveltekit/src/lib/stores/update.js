/**
 * Flagga/signal att flödet skall uppdateras
 */
import { writable } from 'svelte/store';

export const update = writable(false);