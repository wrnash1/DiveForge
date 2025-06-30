import { Schema, model } from 'mongoose';

export interface ICourse {
  tenantId: string;
  agency: string;
  code: string;
  name: string;
  description?: string;
  level: string;
  prerequisites: string[];
  schedule: Array<{
    start: Date;
    end: Date;
    location: string;
    instructorId: string;
    students: string[];
    status: 'scheduled' | 'in-progress' | 'completed' | 'cancelled';
  }>;
  price: number;
  capacity: number;
  createdAt: Date;
  updatedAt: Date;
}

const CourseSchema = new Schema<ICourse>({
  tenantId: { type: String, required: true, index: true },
  agency: { type: String, required: true },
  code: { type: String, required: true },
  name: { type: String, required: true },
  description: String,
  level: { type: String, required: true },
  prerequisites: [{ type: String }],
  schedule: [{
    start: Date,
    end: Date,
    location: String,
    instructorId: String,
    students: [String],
    status: { type: String, enum: ['scheduled', 'in-progress', 'completed', 'cancelled'], default: 'scheduled' }
  }],
  price: { type: Number, required: true },
  capacity: { type: Number, required: true }
}, {
  timestamps: true
});

export const Course = model<ICourse>('Course', CourseSchema);
