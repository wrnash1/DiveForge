import { Schema, model } from 'mongoose';

export interface IAnnouncement {
  tenantId?: string; // null for global/system-wide
  title: string;
  message: string;
  type: 'info' | 'warning' | 'alert' | 'event' | 'update';
  startDate: Date;
  endDate?: Date;
  audience: 'all' | 'staff' | 'customers' | 'admins' | 'custom';
  audienceFilter?: Record<string, any>;
  isActive: boolean;
  createdBy: string;
  createdAt: Date;
  updatedAt: Date;
}

const AnnouncementSchema = new Schema<IAnnouncement>({
  tenantId: { type: String, index: true },
  title: { type: String, required: true },
  message: { type: String, required: true },
  type: { type: String, enum: ['info', 'warning', 'alert', 'event', 'update'], required: true },
  startDate: { type: Date, required: true },
  endDate: Date,
  audience: { type: String, enum: ['all', 'staff', 'customers', 'admins', 'custom'], default: 'all' },
  audienceFilter: Schema.Types.Mixed,
  isActive: { type: Boolean, default: true },
  createdBy: { type: String, required: true }
}, {
  timestamps: true
});

export const Announcement = model<IAnnouncement>('Announcement', AnnouncementSchema);
