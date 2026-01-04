import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import Nav from "./components/Nav.jsx";
import Footer from "./components/Footer.jsx";
import "./styles/Home.css";

const API_URL = "http://localhost:8080";

function Home() {
    const navigate = useNavigate();
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(true);

    // ============================
    // FETCH PRODUITS
    // ============================
    const getItemsList = async () => {
        try {
            const res = await fetch(`${API_URL}/products`, {
                credentials: "include"
            });

            const data = await res.json();

            if (data.success) {
                setProducts(data.data);
            } else {
                setProducts([]);
            }
        } catch (error) {
            console.error("Erreur chargement produits :", error);
            setProducts([]);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        getItemsList();
    }, []);

    // ============================
    // RENDER
    // ============================
    if (loading) {
        return <p>Chargement des produits...</p>;
    }

    return (
        <div className="home-wrapper">
            <div className="navbar-wrapper">
                <div className="navbar">
                    <Nav />
                </div>
            </div>

            <div className="home-title-wrapper">
                <h1 className="home-title">Balmung Store</h1>
            </div>

            <div className="home-introduction-text-wrapper">
                <p className="home-introduction-text">
                    Voici la liste de quelques uns de nos produits phares.
                </p>
            </div>

            <div className="product-carrousel-wrapper">
                <ul className="product-carrousel">
                    {products.map(product => (
                        <li key={product.id_produit} className="product-item">
                            <span>{product.nom_produit}</span>
                            <div className="product-img-container">
                                <img src={product.image_produit} alt={product.nom_produit}/>
                            </div>
                            <button
                                className="product-more-btn"
                                onClick={() => navigate(`/detail/${product.id_produit}`)}
                            >
                                En savoir plus
                            </button>
                        </li>
                    ))}
                </ul>
            </div>

            <div className="footer-wrapper">
                <div className="footer">
                    <Footer />
                </div>
            </div>
        </div>
    );
}

export default Home;
