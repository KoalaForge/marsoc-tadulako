/**
 * Picks the right string out of a bilingual field ({ en, id }) or passes a
 * plain string through unchanged (for fields that don't vary by locale).
 */
export function localized(field: any, locale: string): any {
  if (field == null) return field
  if (typeof field === 'string') return field
  return field[locale] ?? field.en ?? Object.values(field)[0]
}
