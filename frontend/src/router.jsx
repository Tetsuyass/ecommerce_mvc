import {Routes, Route} from 'react-router-dom';
import Home from './Home.jsx';
import Panier from './views/Panier.jsx';
import LoginUser from './views/connexion/LoginUser.jsx';
import DetailProduit from './views/produit/DetailProduit.jsx';
import EspaceClient from "./views/connexion/EspaceClient.jsx";
import ProtectedRoute from "./components/ProtectedRoute.jsx";

export default function AppRouter() {
    return (
      <Routes>
          <Route path="/" element={<Home />}/>
          <Route path="/panier" element={<Panier />}/>
          <Route path="/login" element={<LoginUser />}/>
          <Route path="/detail/:id" element={<DetailProduit />}/>
          import ProtectedRoute from "./components/ProtectedRoute";
          <Route
              path="/user-space"
              element={
                  <ProtectedRoute>
                      <EspaceClient />
                  </ProtectedRoute>
              }
          />
      </Routes>
    );
}