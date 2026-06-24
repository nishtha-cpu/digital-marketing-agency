const express = require('express');
const router = express.Router();
const {
  createLead,
  getLeads,
  updateLeadStatus,
  deleteLead,
} = require('../controllers/leadController');
const { protect, authorize } = require('../middleware/authMiddleware');

// Public route to submit contact forms
router.post('/', createLead);

// Protected Admin-only routes
router.get('/', protect, authorize('admin'), getLeads);
router.patch('/:id/status', protect, authorize('admin'), updateLeadStatus);
router.delete('/:id', protect, authorize('admin'), deleteLead);

module.exports = router;
