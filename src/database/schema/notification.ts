import { Schema, model } from 'mongoose';

export interface INotification {
  tenantId: string;
  userId?: string; // null for broadcast/system notifications
  type: string; // e.g. 'info', 'warning', 'alert', 'reminder'
  title: string;
  message: string;
  read: boolean;
  relatedResource?: string; // e.g. bookingId, incidentId, etc.
  createdAt: Date;
  updatedAt: Date;
}

const NotificationSchema = new Schema<INotification>({
  tenantId: { type: String, required: true, index: true },
  userId: String,
  type: { type: String, required: true },
  title: { type: String, required: true },
  message: { type: String, required: true },
  read: { type: Boolean, default: false },
  relatedResource: String
}, {
  timestamps: true
});

export const Notification = model<INotification>('Notification', NotificationSchema);
