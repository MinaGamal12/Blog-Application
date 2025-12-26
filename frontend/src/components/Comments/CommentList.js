import React from 'react';

function CommentList({ comments, onDelete, currentUserId }) {
  return (
    <div>
      {comments.length === 0 ? (
        <p style={{ color: '#666', fontStyle: 'italic' }}>No comments yet.</p>
      ) : (
        comments.map(comment => (
          <div
            key={comment.id}
            style={{
              padding: '15px',
              marginBottom: '10px',
              background: '#f9f9f9',
              borderRadius: '5px',
            }}
          >
            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '5px' }}>
              <div>
                <strong>{comment.user?.name || 'Anonymous'}</strong>
                <span style={{ color: '#666', marginLeft: '10px', fontSize: '14px' }}>
                  {new Date(comment.created_at).toLocaleString()}
                </span>
              </div>
              {currentUserId === comment.user_id && (
                <button
                  onClick={() => onDelete(comment.id)}
                  className="btn btn-danger"
                  style={{ padding: '5px 10px', fontSize: '12px' }}
                >
                  Delete
                </button>
              )}
            </div>
            <p style={{ margin: 0 }}>{comment.body}</p>
          </div>
        ))
      )}
    </div>
  );
}

export default CommentList;

