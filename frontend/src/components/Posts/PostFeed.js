import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../../services/api';
import PostCard from './PostCard';

function PostFeed() {
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const navigate = useNavigate();

  useEffect(() => {
    fetchPosts();
    // Auto-refresh every 30 seconds
    const interval = setInterval(fetchPosts, 30000);
    return () => clearInterval(interval);
  }, []);

  const fetchPosts = async () => {
    try {
      const response = await api.get('/posts');
      setPosts(response.data);
      setError('');
    } catch (err) {
      setError('Failed to load posts');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (postId) => {
    if (window.confirm('Are you sure you want to delete this post?')) {
      try {
        await api.delete(`/posts/${postId}`);
        setPosts(posts.filter(post => post.id !== postId));
      } catch (err) {
        alert('Failed to delete post');
      }
    }
  };

  if (loading) {
    return <div style={{ textAlign: 'center', padding: '50px' }}>Loading...</div>;
  }

  return (
    <div>
      <h1 style={{ marginBottom: '30px' }}>Blog Posts</h1>
      {error && <div className="error">{error}</div>}
      {posts.length === 0 ? (
        <div className="card" style={{ textAlign: 'center', padding: '50px' }}>
          <p>No posts yet. <button onClick={() => navigate('/posts/create')} className="btn btn-primary">Create your first post!</button></p>
        </div>
      ) : (
        posts.map(post => (
          <PostCard
            key={post.id}
            post={post}
            onDelete={handleDelete}
            onEdit={() => navigate(`/posts/${post.id}/edit`)}
          />
        ))
      )}
    </div>
  );
}

export default PostFeed;

