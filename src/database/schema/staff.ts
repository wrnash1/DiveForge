import { Schema, model } from 'mongoose';

export interface IStaff {
  tenantId: string;
  userId: string;
  name: string;
  email: string;
  roles: string[]; // e.g. ['instructor', 'manager', 'service']
  certifications: Array<{
    agency: string;
    level: string;
    certNumber?: string;
    expiryDate?: Date;
  }>;
  employmentStatus: 'active' | 'inactive' | 'terminated';
  hireDate?: Date;
  terminationDate?: Date;
  performanceNotes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const StaffSchema = new Schema<IStaff>({
  tenantId: { type: String, required: true, index: true },
  userId: { type: String, required: true },
  name: { type: String, required: true },
  email: { type: String, required: true },
  roles: [{ type: String, required: true }],
  certifications: [{
    agency: String,
    level: String,
    certNumber: String,
    expiryDate: Date
  }],
  employmentStatus: { type: String, enum: ['active', 'inactive', 'terminated'], default: 'active' },
  hireDate: Date,
  terminationDate: Date,
  performanceNotes: String
}, {
  timestamps: true
});

export const Staff = model<IStaff>('Staff', StaffSchema);
