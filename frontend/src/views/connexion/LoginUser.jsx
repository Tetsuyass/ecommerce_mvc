import '../../styles/LoginUser.css'
import Nav from "../../components/Nav.jsx";
import Footer from "../../components/Footer.jsx";
import { useState, useContext } from "react";
import { useNavigate } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext.jsx";

export default function LoginUser() {

    const navigate = useNavigate();
    const { setUser } = useContext(AuthContext);

    const [email, setEmail] = useState("");
    const [mdp, setMdp] = useState("");
    const [mode, setMode] = useState("login"); // login | register

    const [error, setError] = useState(null);
    const [success, setSuccess] = useState(null);
    const [loading, setLoading] = useState(false);

    async function handleSubmit(e) {
        e.preventDefault();
        setError(null);
        setSuccess(null);
        setLoading(true);

        const endpoint =
            mode === "login"
                ? "http://localhost:8080/auth/login"
                : "http://localhost:8080/auth/register";

        try {
            const response = await fetch(endpoint, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                credentials: "include",
                body: JSON.stringify({
                    email: email,
                    password: mdp,
                }),
            });

            const data = await response.json();

            if (!response.ok || data.success === false) {
                throw new Error(data.message || "Erreur");
            }

            // -------------------------
            // 🔐 MODE LOGIN
            // -------------------------
            if (mode === "login") {

                // Récupérer l'utilisateur connecté
                const meRes = await fetch("http://localhost:8080/auth/me", {
                    credentials: "include"
                });
                const meData = await meRes.json();

                if (meData.success) {
                    setUser(meData.data.user); // mise à jour du contexte
                }

                navigate("/");
                return;
            }

            // -------------------------
            // 🆕 MODE REGISTER
            // -------------------------
            setSuccess("Compte créé avec succès. Vous pouvez vous connecter.");
            setMode("login");
            setMdp("");

        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    }

    return (
        <div className='login-user-wrapper'>
            <div className='login-user-title-wrapper'>
                <h2 className='login-user-title'>
                    {mode === "login" ? "Connexion" : "Créer un compte"}
                </h2>
            </div>

            <div className='navbar-wrapper'>
                <Nav />
            </div>

            <div className='login-user-form-wrapper'>
                <form className='login-user-form' onSubmit={handleSubmit}>
                    <input
                        type='email'
                        name='email'
                        placeholder='Email'
                        value={email}
                        onChange={e => setEmail(e.target.value)}
                        required
                    />

                    <input
                        type='password'
                        name='mdp'
                        placeholder='Mot de passe'
                        value={mdp}
                        onChange={e => setMdp(e.target.value)}
                        required
                    />

                    {error && <p className='login-error'>{error}</p>}
                    {success && <p className='login-success'>{success}</p>}

                    <button type='submit' disabled={loading}>
                        {loading
                            ? "Traitement..."
                            : mode === "login"
                                ? "Se connecter"
                                : "Créer le compte"}
                    </button>
                </form>

                <div className='login-user-switch'>
                    {mode === "login" ? (
                        <p>
                            Pas encore de compte ?{" "}
                            <button
                                type='button'
                                onClick={() => {
                                    setMode("register");
                                    setError(null);
                                    setSuccess(null);
                                }}
                            >
                                Créer un compte
                            </button>
                        </p>
                    ) : (
                        <p>
                            Déjà un compte ?{" "}
                            <button
                                type='button'
                                onClick={() => {
                                    setMode("login");
                                    setError(null);
                                    setSuccess(null);
                                }}
                            >
                                Se connecter
                            </button>
                        </p>
                    )}
                </div>
            </div>

            <div className='footer-wrapper'>
                <Footer />
            </div>
        </div>
    );
}
