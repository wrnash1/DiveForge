import express from 'express';
import cors from 'cors';
import { tenantMiddleware } from './middleware/tenant';
import { connectDatabase } from './database/connection';
import { agencyRoutes } from './routes/agency';
import { customerRoutes } from './routes/customer';
import { configureSecurityMiddleware } from './middleware/security';
import { authRoutes } from './routes/auth';
import { courseRoutes } from './routes/course';
import { equipmentRoutes } from './routes/equipment';
import { reportRoutes } from './routes/report';
import { bookingRoutes } from './routes/booking';
import { staffRoutes } from './routes/staff';
import { payrollRoutes } from './routes/payroll';
import { vendorRoutes } from './routes/vendor';
import { purchaseOrderRoutes } from './routes/purchaseOrder';
import { loyaltyRoutes } from './routes/loyalty';
import { incidentRoutes } from './routes/incident';
import { formRoutes } from './routes/form';
import { formSubmissionRoutes } from './routes/formSubmission';
import { analyticsRoutes } from './routes/analytics';
import { notificationRoutes } from './routes/notification';
import { auditLogRoutes } from './routes/auditLog';
import { commissionRoutes } from './routes/commission';
import { materialRoutes } from './routes/material';
import { serviceOrderRoutes } from './routes/serviceOrder';
import { giftCardRoutes } from './routes/giftCard';
import { airCardRoutes } from './routes/airCard';
import { tripRoutes } from './routes/trip';
import { boatRoutes } from './routes/boat';
import { diveSiteRoutes } from './routes/diveSite';
import { b2bAccountRoutes } from './routes/b2bAccount';
import { diveLogRoutes } from './routes/diveLog';
import { buddyRoutes } from './routes/buddy';
import { eventRoutes } from './routes/event';
import { photoRoutes } from './routes/photo';
import { forumRoutes } from './routes/forum';
import { reviewRoutes } from './routes/review';
import { integrationRoutes } from './routes/integration';
import { pluginRoutes } from './routes/plugin';
import { themeRoutes } from './routes/theme';
import { mobileRoutes } from './routes/mobile';
import { iotRoutes } from './routes/iot';
import { licenseRoutes } from './routes/license';
import { webhookRoutes } from './routes/webhook';
import { franchiseRoutes } from './routes/franchise';
import { subscriptionRoutes } from './routes/subscription';
import { organizationRoutes } from './routes/organization';
import { automationRoutes } from './routes/automation';
import { paymentRoutes } from './routes/payment';
import { announcementRoutes } from './routes/announcement';
import { reportTemplateRoutes } from './routes/reportTemplate';
import { exportRoutes } from './routes/export';
import { importRoutes } from './routes/import';
import { logRoutes } from './routes/log';
import { alertRoutes } from './routes/alert';

const app = express();
const PORT = process.env.PORT || 3000;

// Basic middleware
app.use(express.json());
app.use(cors());
app.use(configureSecurityMiddleware());

// Multi-tenant support
app.use(tenantMiddleware);

// API routes
app.use('/api/v1/agencies', agencyRoutes);
app.use('/api/v1/customers', customerRoutes);
app.use('/api/v1/auth', authRoutes);
app.use('/api/v1/courses', courseRoutes);
app.use('/api/v1/equipment', equipmentRoutes);
app.use('/api/v1/reports', reportRoutes);
app.use('/api/v1/bookings', bookingRoutes);
app.use('/api/v1/staff', staffRoutes);
app.use('/api/v1/payroll', payrollRoutes);
app.use('/api/v1/vendors', vendorRoutes);
app.use('/api/v1/purchase-orders', purchaseOrderRoutes);
app.use('/api/v1/loyalty', loyaltyRoutes);
app.use('/api/v1/incidents', incidentRoutes);
app.use('/api/v1/forms', formRoutes);
app.use('/api/v1/form-submissions', formSubmissionRoutes);
app.use('/api/v1/analytics', analyticsRoutes);
app.use('/api/v1/notifications', notificationRoutes);
app.use('/api/v1/audit-logs', auditLogRoutes);
app.use('/api/v1/commissions', commissionRoutes);
app.use('/api/v1/materials', materialRoutes);
app.use('/api/v1/service-orders', serviceOrderRoutes);
app.use('/api/v1/gift-cards', giftCardRoutes);
app.use('/api/v1/air-cards', airCardRoutes);
app.use('/api/v1/trips', tripRoutes);
app.use('/api/v1/boats', boatRoutes);
app.use('/api/v1/dive-sites', diveSiteRoutes);
app.use('/api/v1/b2b-accounts', b2bAccountRoutes);
app.use('/api/v1/dive-logs', diveLogRoutes);
app.use('/api/v1/buddies', buddyRoutes);
app.use('/api/v1/events', eventRoutes);
app.use('/api/v1/photos', photoRoutes);
app.use('/api/v1/forums', forumRoutes);
app.use('/api/v1/reviews', reviewRoutes);
app.use('/api/v1/integrations', integrationRoutes);
app.use('/api/v1/plugins', pluginRoutes);
app.use('/api/v1/themes', themeRoutes);
app.use('/api/v1/mobile', mobileRoutes);
app.use('/api/v1/iot', iotRoutes);
app.use('/api/v1/licenses', licenseRoutes);
app.use('/api/v1/webhooks', webhookRoutes);
app.use('/api/v1/franchises', franchiseRoutes);
app.use('/api/v1/subscriptions', subscriptionRoutes);
app.use('/api/v1/organizations', organizationRoutes);
app.use('/api/v1/automation', automationRoutes);
app.use('/api/v1/payments', paymentRoutes);
app.use('/api/v1/announcements', announcementRoutes);
app.use('/api/v1/report-templates', reportTemplateRoutes);
app.use('/api/v1/exports', exportRoutes);
app.use('/api/v1/imports', importRoutes);
app.use('/api/v1/logs', logRoutes);
app.use('/api/v1/alerts', alertRoutes);

// Error handling
app.use((err: Error, req: express.Request, res: express.Response, next: express.NextFunction) => {
  console.error(err.stack);
  res.status(500).json({ error: 'Internal Server Error' });
});

// Start server
async function startServer() {
  try {
    await connectDatabase();
    app.listen(PORT, () => {
      console.log(`DiveForge server running on port ${PORT}`);
    });
  } catch (error) {
    console.error('Failed to start server:', error);
    process.exit(1);
  }
}

startServer();
