const express = require('express');
const router = express.Router();
const {
  getBlogPosts,
  getAdminBlogPosts,
  getBlogPostBySlug,
  createBlogPost,
  updateBlogPost,
  deleteBlogPost,
} = require('../controllers/blogController');
const { protect, authorize } = require('../middleware/authMiddleware');

// Public routes
router.get('/', getBlogPosts);
router.get('/:slug', getBlogPostBySlug);

// Private/Protected routes for admin or authors
router.get('/admin/all', protect, authorize('admin', 'author'), getAdminBlogPosts);
router.post('/', protect, authorize('admin', 'author'), createBlogPost);
router.put('/:id', protect, authorize('admin', 'author'), updateBlogPost);
router.delete('/:id', protect, authorize('admin', 'author'), deleteBlogPost);

module.exports = router;
