export type Role = "student" | "teacher" | "coordinator";

export type User = {
    id: number;
    section_id: number | null; // seulement pour les étudiants
    role: Role;
    first_name: string;
    last_name: string;
    name: string; // accesseur calculé côté Laravel (first_name + last_name), pas une colonne
    email: string;
    address: string | null;
    phone: string | null;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};
