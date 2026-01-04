import { useContext } from "react";
import { Navigate } from "react-router-dom";
import { AuthContext } from "../context/AuthContext";

export default function ProtectedRoute({ children }) {
    const { user, loading } = useContext(AuthContext);

    // Pendant la vérification de /auth/me
    if (loading) {
        return <p>Chargement...</p>;
    }

    // Si pas connecté → redirection
    if (!user) {
        return <Navigate to="/login" replace />;
    }

    // Si connecté → on affiche la page
    return children;
}
