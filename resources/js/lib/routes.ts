/**
 * Prefixes a bare path with the active locale segment. Slugs are
 * deliberately locale-independent (see HandleInertiaRequests::mirroredUrls),
 * so this is a pure string op — no route table, no lookup.
 */
export function withLocale(locale: string, path: string): string {
  const rest = path.startsWith('/') ? path.slice(1) : path
  return rest === '' ? `/${locale}` : `/${locale}/${rest}`
}
