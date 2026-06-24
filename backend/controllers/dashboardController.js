const Lead = require('../models/Lead');
const BlogPost = require('../models/BlogPost');
const Service = require('../models/Service');
const Subscriber = require('../models/Subscriber');

// @desc    Get dashboard statistics
// @route   GET /api/dashboard
// @access  Private/Admin
const getDashboardStats = async (req, res, next) => {
  try {
    const [totalLeads, totalBlogs, totalServices, totalSubscribers] = await Promise.all([
      Lead.countDocuments({}),
      BlogPost.countDocuments({}),
      Service.countDocuments({}),
      Subscriber.countDocuments({ active: true }),
    ]);

    res.json({
      success: true,
      data: {
        totalLeads,
        totalBlogs,
        totalServices,
        totalSubscribers,
      },
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  getDashboardStats,
};
