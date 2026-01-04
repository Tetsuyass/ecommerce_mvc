import { useEffect, useState } from "react";

const API_URL = "http://localhost:8080";

export default function useAuth() {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const checkAuth = async () => {
            try {
                const res = await fetch(`${API_URL}/auth/me`, {
                    credentials: "include"
                });

                if (res.ok) {
                    const data = await res.json();
                    setUser(data.user);
                } else {
                    setUser(null);
                }
            } catch (e) {
                setUser(null);
            } finally {
                setLoading(false);
            }
        };

        checkAuth();
    }, []);

    return { user, loading };
}
