const Lead = require('../models/Lead');

// @desc    Submit a contact form (Create Lead)
// @route   POST /api/leads
// @access  Public
const createLead = async (req, res, next) => {
  try {
    const { name, email, phone, serviceInterest, message } = req.body;

    if (!name || !email || !message) {
      res.status(400);
      throw new Error('Please provide name, email, and message');
    }

    const lead = await Lead.create({
      name,
      email,
      phone,
      serviceInterest,
      message,
    });

    res.status(201).json({
      success: true,
      message: 'Contact form submitted successfully',
      data: lead,
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Get all leads
// @route   GET /api/leads
// @access  Private/Admin
const getLeads = async (req, res, next) => {
  try {
    const statusFilter = req.query.status;
    const query = statusFilter ? { status: statusFilter } : {};

    const leads = await Lead.find(query).sort({ createdAt: -1 });

    res.json({
      success: true,
      count: leads.length,
      data: leads,
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Update lead status
// @route   PATCH /api/leads/:id/status
// @access  Private/Admin
const updateLeadStatus = async (req, res, next) => {
  try {
    const { status } = req.body;

    if (!status || !['new', 'contacted', 'closed'].includes(status)) {
      res.status(400);
      throw new Error('Please provide a valid status: new, contacted, or closed');
    }

    const lead = await Lead.findById(req.params.id);

    if (!lead) {
      res.status(404);
      throw new Error('Lead not found');
    }

    lead.status = status;
    await lead.save();

    res.json({
      success: true,
      message: `Lead status updated to ${status}`,
      data: lead,
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Delete a lead
// @route   DELETE /api/leads/:id
// @access  Private/Admin
const deleteLead = async (req, res, next) => {
  try {
    const lead = await Lead.findById(req.params.id);

    if (!lead) {
      res.status(404);
      throw new Error('Lead not found');
    }

    await lead.deleteOne();

    res.json({
      success: true,
      message: 'Lead deleted successfully',
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  createLead,
  getLeads,
  updateLeadStatus,
  deleteLead,
};
