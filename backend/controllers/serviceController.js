const Service = require('../models/Service');

// @desc    Get all active services
// @route   GET /api/services
// @access  Public
const getServices = async (req, res, next) => {
  try {
    const services = await Service.find({ active: true }).sort({ name: 1 });

    res.json({
      success: true,
      count: services.length,
      data: services,
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Get all services (including inactive) for admin
// @route   GET /api/services/admin
// @access  Private/Admin
const getAdminServices = async (req, res, next) => {
  try {
    const services = await Service.find({}).sort({ name: 1 });

    res.json({
      success: true,
      count: services.length,
      data: services,
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Create a service
// @route   POST /api/services
// @access  Private/Admin
const createService = async (req, res, next) => {
  try {
    const { name, description, icon, features, price, active } = req.body;

    if (!name || !description) {
      res.status(400);
      throw new Error('Please enter name and description');
    }

    const serviceExists = await Service.findOne({ name });

    if (serviceExists) {
      res.status(400);
      throw new Error('Service with this name already exists');
    }

    const service = await Service.create({
      name,
      description,
      icon,
      features,
      price,
      active,
    });

    res.status(201).json({
      success: true,
      message: 'Service created successfully',
      data: service,
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Update a service
// @route   PUT /api/services/:id
// @access  Private/Admin
const updateService = async (req, res, next) => {
  try {
    const service = await Service.findById(req.params.id);

    if (!service) {
      res.status(404);
      throw new Error('Service not found');
    }

    const { name, description, icon, features, price, active } = req.body;

    service.name = name !== undefined ? name : service.name;
    service.description = description !== undefined ? description : service.description;
    service.icon = icon !== undefined ? icon : service.icon;
    service.features = features !== undefined ? features : service.features;
    service.price = price !== undefined ? price : service.price;
    service.active = active !== undefined ? active : service.active;

    const updatedService = await service.save();

    res.json({
      success: true,
      message: 'Service updated successfully',
      data: updatedService,
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Delete a service
// @route   DELETE /api/services/:id
// @access  Private/Admin
const deleteService = async (req, res, next) => {
  try {
    const service = await Service.findById(req.params.id);

    if (!service) {
      res.status(404);
      throw new Error('Service not found');
    }

    await service.deleteOne();

    res.json({
      success: true,
      message: 'Service deleted successfully',
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Get single service by ID
// @route   GET /api/services/:id
// @access  Public
const getServiceById = async (req, res, next) => {
  try {
    const service = await Service.findById(req.params.id);

    if (!service) {
      res.status(404);
      throw new Error('Service not found');
    }

    res.json({
      success: true,
      data: service,
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  getServices,
  getAdminServices,
  createService,
  updateService,
  deleteService,
  getServiceById,
};
