type TypeSkeleton = "text" | "image" | "avatar" | 'span' | "random" | "input" | "custom";
export interface ModelSkeleton{
    type: TypeSkeleton;
    className?: string;
}