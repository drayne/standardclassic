export interface Article {
    id: number
    slug: string
    title: string | null
    content: string | null
    image: string | null
    images: string[]
    image_source: string | null
    article_source: string | null
    published_at: string | null
}
