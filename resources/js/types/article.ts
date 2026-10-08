export interface ArticleImage {
    url: string
    thumbnail: string
}

export interface Article {
    id: number
    slug: string
    title: string | null
    content: string | null
    image: string | null
    thumbnail: string | null
    images: ArticleImage[]
    image_source: string | null
    article_source: string | null
    published_at: string | null
}
