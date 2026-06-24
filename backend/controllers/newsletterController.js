const Subscriber = require('../models/Subscriber');

// @desc    Subscribe email to newsletter
// @route   POST /api/newsletter/subscribe
// @access  Public
const subscribe = async (req, res, next) => {
  try {
    const { email } = req.body;

    if (!email) {
      res.status(400);
      throw new Error('Please provide an email address');
    }

    // Check if subscriber exists
    let subscriber = await Subscriber.findOne({ email });

    if (subscriber) {
      if (subscriber.active) {
        res.status(400);
        throw new Error('Email is already subscribed');
      } else {
        // Reactivate subscription
        subscriber.active = true;
        await subscriber.save();
        return res.json({
          success: true,
          message: 'Subscribed to newsletter successfully (reactivated)',
          data: subscriber,
        });
      }
    }

    // Create new subscription
    subscriber = await Subscriber.create({ email });

    res.status(201).json({
      success: true,
      message: 'Subscribed to newsletter successfully',
      data: subscriber,
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Unsubscribe email from newsletter
// @route   POST /api/newsletter/unsubscribe
// @access  Public
const unsubscribe = async (req, res, next) => {
  try {
    const { email } = req.body;

    if (!email) {
      res.status(400);
      throw new Error('Please provide an email address');
    }

    const subscriber = await Subscriber.findOne({ email });

    if (!subscriber || !subscriber.active) {
      res.status(404);
      throw new Error('Email subscription not found or already inactive');
    }

    subscriber.active = false;
    await subscriber.save();

    res.json({
      success: true,
      message: 'Unsubscribed from newsletter successfully',
      data: subscriber,
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Get all subscribers
// @route   GET /api/newsletter/subscribers
// @access  Private/Admin
const getSubscribers = async (req, res, next) => {
  try {
    const activeFilter = req.query.active;
    const query = activeFilter !== undefined ? { active: activeFilter === 'true' } : {};

    const subscribers = await Subscriber.find(query).sort({ createdAt: -1 });

    res.json({
      success: true,
      count: subscribers.length,
      data: subscribers,
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  subscribe,
  unsubscribe,
  getSubscribers,
};
