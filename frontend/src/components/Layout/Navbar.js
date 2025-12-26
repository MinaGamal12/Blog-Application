import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';

function Navbar() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  if (!user) {
    return null;
  }

  return (
    <nav style={{
      background: '#fff',
      padding: '15px 0',
      boxShadow: '0 2px 4px rgba(0,0,0,0.1)',
      marginBottom: '20px'
    }}>
      <div className="container" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
        <Link to="/" style={{ textDecoration: 'none', color: '#333', fontSize: '24px', fontWeight: 'bold' }}>
          Blog App
        </Link>
        <div style={{ display: 'flex', alignItems: 'center', gap: '20px' }}>
          <Link to="/posts/create" className="btn btn-primary" style={{ textDecoration: 'none' }}>
            Create Post
          </Link>
          <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
            {user.image && (
              <img
                src={`http://localhost:8000/storage/${user.image}`}
                alt={user.name}
                style={{ width: '40px', height: '40px', borderRadius: '50%' }}
              />
            )}
            <span>{user.name}</span>
          </div>
          <button onClick={handleLogout} className="btn btn-secondary">
            Logout
          </button>
        </div>
      </div>
    </nav>
  );
}

export default Navbar;

