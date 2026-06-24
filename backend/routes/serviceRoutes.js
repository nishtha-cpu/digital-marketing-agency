const express = require('express');
const router = express.Router();
const {
  getServices,
  getAdminServices,
  createService,
  updateService,
  deleteService,
  getServiceById,
} = require('../controllers/serviceController');
const { protect, authorize } = require('../middleware/authMiddleware');

// Public routes
router.get('/', getServices);

// Protected Admin-only routes
router.get('/admin/all', protect, authorize('admin'), getAdminServices);

// Public single service route (placed after admin/all to prevent routing conflicts)
router.get('/:id', getServiceById);

router.post('/', protect, authorize('admin'), createService);
router.put('/:id', protect, authorize('admin'), updateService);
router.delete('/:id', protect, authorize('admin'), deleteService);

module.exports = router;
