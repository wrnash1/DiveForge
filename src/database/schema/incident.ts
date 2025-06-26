import { Schema, model } from 'mongoose';

export interface IIncident {
  tenantId: string;
  date: Date;
  reportedBy: string;
  type: string; // e.g. 'injury', 'equipment', 'safety', 'other'
  description: string;
  location?: string;
  involvedStaff?: string[];
  involvedCustomers?: string[];
  actionsTaken?: string;
  status: 'open' | 'investigating' | 'closed';
  attachments?: string[];
  createdAt: Date;
  updatedAt: Date;
}

const IncidentSchema = new Schema<IIncident>({
  tenantId: { type: String, required: true, index: true },
  date: { type: Date, required: true },
  reportedBy: { type: String, required: true },
  type: { type: String, required: true },
  description: { type: String, required: true },
  location: String,
  involvedStaff: [String],
  involvedCustomers: [String],
  actionsTaken: String,
  status: { type: String, enum: ['open', 'investigating', 'closed'], default: 'open' },
  attachments: [String]
}, {
  timestamps: true
});

export const Incident = model<IIncident>('Incident', IncidentSchema);
