export interface ModelPagination{
    currentPage: number;
    totalPage: number;
    countItem: number;
    totalItem: number;
    className?: string;
}

export interface ModelPaginationLink {
    className?: string,
    children: React.ReactNode,
    isActive?: boolean
}