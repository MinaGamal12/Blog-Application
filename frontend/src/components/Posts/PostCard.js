import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import api from '../../services/api';
import CommentList from '../Comments/CommentList';
import TagManager from '../Tags/TagManager';

function PostCard({ post, onDelete, onEdit }) {
  const { user } = useAuth();
  const [showComments, setShowComments] = useState(false);
  const [comments, setComments] = useState(post.comments || []);
  const [newComment, setNewComment] = useState('');
  const [isExpired, setIsExpired] = useState(post.is_expired);
  const [timeRemaining, setTimeRemaining] = useState(null);

  React.useEffect(() => {
    const updateExpiry = () => {
      if (post.expires_at) {
        const expiresAt = new Date(post.expires_at);
        const now = new Date();
        const diff = expiresAt - now;

        if (diff <= 0) {
          setIsExpired(true);
          setTimeRemaining('Expired');
        } else {
          setIsExpired(false);
          const hours = Math.floor(diff / (1000 * 60 * 60));
          const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
          setTimeRemaining(`${hours}h ${minutes}m remaining`);
        }
      }
    };

    updateExpiry();
    const interval = setInterval(updateExpiry, 60000); // Update every minute
    return () => clearInterval(interval);
  }, [post.expires_at]);

  const handleAddComment = async (e) => {
    e.preventDefault();
    if (!newComment.trim()) return;

    try {
      const response = await api.post(`/posts/${post.id}/comments`, {
        body: newComment,
      });
      setComments([...comments, response.data.comment]);
      setNewComment('');
    } catch (err) {
      alert('Failed to add comment');
    }
  };

  const handleDeleteComment = async (commentId) => {
    try {
      await api.delete(`/comments/${commentId}`);
      setComments(comments.filter(c => c.id !== commentId));
    } catch (err) {
      alert('Failed to delete comment');
    }
  };

  const isOwner = user && post.author.id === user.id;
  const expiryClass = isExpired ? 'expired' : timeRemaining && parseInt(timeRemaining) < 1 ? 'expiring-soon' : '';

  return (
    <div className="card">
      <div className="card-header">
        <div>
          <h2 className="card-title">{post.title}</h2>
          {timeRemaining && (
            <span className={`expired-badge ${expiryClass}`}>
              {timeRemaining}
            </span>
          )}
        </div>
        <div style={{ display: 'flex', gap: '10px' }}>
          {isOwner && (
            <>
              <button onClick={onEdit} className="btn btn-secondary">Edit</button>
              <button onClick={() => onDelete(post.id)} className="btn btn-danger">Delete</button>
            </>
          )}
        </div>
      </div>
      <div className="card-body">
        <p>{post.body}</p>
        <div style={{ marginTop: '15px', fontSize: '14px', color: '#666' }}>
          By {post.author.name} • {new Date(post.created_at).toLocaleString()}
        </div>
      </div>

      <div style={{ marginTop: '20px' }}>
        <TagManager post={post} isOwner={isOwner} />
      </div>

      <div style={{ marginTop: '20px', borderTop: '1px solid #eee', paddingTop: '20px' }}>
        <button
          onClick={() => setShowComments(!showComments)}
          className="btn btn-secondary"
          style={{ marginBottom: '15px' }}
        >
          {showComments ? 'Hide' : 'Show'} Comments ({comments.length})
        </button>

        {showComments && (
          <>
            <CommentList
              comments={comments}
              onDelete={handleDeleteComment}
              currentUserId={user?.id}
            />
            <form onSubmit={handleAddComment} style={{ marginTop: '15px' }}>
              <div className="form-group">
                <textarea
                  value={newComment}
                  onChange={(e) => setNewComment(e.target.value)}
                  placeholder="Add a comment..."
                  style={{ minHeight: '80px' }}
                />
              </div>
              <button type="submit" className="btn btn-primary">Add Comment</button>
            </form>
          </>
        )}
      </div>
    </div>
  );
}

export default PostCard;

