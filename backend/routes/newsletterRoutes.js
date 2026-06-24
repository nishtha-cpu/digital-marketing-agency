const express = require('express');
const router = express.Router();
const {
  subscribe,
  unsubscribe,
  getSubscribers,
} = require('../controllers/newsletterController');
const { protect, authorize } = require('../middleware/authMiddleware');

// Public subscription routes
router.post('/subscribe', subscribe);
router.post('/unsubscribe', unsubscribe);

// Protected Admin-only routes
router.get('/subscribers', protect, authorize('admin'), getSubscribers);

module.exports = router;
