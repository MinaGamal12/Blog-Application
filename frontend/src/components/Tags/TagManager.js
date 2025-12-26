import React, { useState } from 'react';
import api from '../../services/api';

function TagManager({ post, isOwner }) {
  const [tags, setTags] = useState(post.tags || []);
  const [tagInput, setTagInput] = useState('');
  const [editing, setEditing] = useState(false);
  const [loading, setLoading] = useState(false);

  const handleAddTag = async (e) => {
    e.preventDefault();
    if (!tagInput.trim() || tags.some(t => t.name === tagInput.trim())) return;

    const newTag = { name: tagInput.trim() };
    const updatedTags = [...tags, newTag];

    if (isOwner) {
      setLoading(true);
      try {
        await api.put(`/posts/${post.id}/tags`, {
          tags: updatedTags.map(t => t.name),
        });
        setTags(updatedTags);
        setTagInput('');
      } catch (err) {
        alert('Failed to update tags');
      } finally {
        setLoading(false);
      }
    }
  };

  const handleRemoveTag = async (tagToRemove) => {
    const updatedTags = tags.filter(t => t.name !== tagToRemove.name);

    if (isOwner) {
      setLoading(true);
      try {
        await api.put(`/posts/${post.id}/tags`, {
          tags: updatedTags.map(t => t.name),
        });
        setTags(updatedTags);
      } catch (err) {
        alert('Failed to update tags');
      } finally {
        setLoading(false);
      }
    }
  };

  return (
    <div>
      <div style={{ display: 'flex', alignItems: 'center', gap: '10px', marginBottom: '10px' }}>
        <strong>Tags:</strong>
        {tags.map((tag, index) => (
          <span
            key={index}
            style={{
              background: '#6c757d',
              color: 'white',
              padding: '5px 10px',
              borderRadius: '5px',
              fontSize: '14px',
              display: 'inline-flex',
              alignItems: 'center',
              gap: '5px',
            }}
          >
            {tag.name}
            {isOwner && (
              <button
                onClick={() => handleRemoveTag(tag)}
                style={{
                  background: 'transparent',
                  border: 'none',
                  color: 'white',
                  cursor: 'pointer',
                  fontSize: '16px',
                  padding: 0,
                }}
                disabled={loading}
              >
                ×
              </button>
            )}
          </span>
        ))}
        {isOwner && (
          <button
            onClick={() => setEditing(!editing)}
            className="btn btn-secondary"
            style={{ padding: '5px 10px', fontSize: '12px' }}
          >
            {editing ? 'Cancel' : 'Edit Tags'}
          </button>
        )}
      </div>
      {editing && isOwner && (
        <form onSubmit={handleAddTag} style={{ display: 'flex', gap: '10px' }}>
          <input
            type="text"
            value={tagInput}
            onChange={(e) => setTagInput(e.target.value)}
            placeholder="Add a tag"
            style={{ flex: 1, padding: '5px' }}
            disabled={loading}
          />
          <button type="submit" className="btn btn-primary" disabled={loading} style={{ padding: '5px 15px' }}>
            Add
          </button>
        </form>
      )}
    </div>
  );
}

export default TagManager;

