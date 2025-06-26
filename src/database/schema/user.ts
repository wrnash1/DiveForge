import { Schema, model } from 'mongoose';

export interface IUser {
  tenantId: string;
  email: string;
  passwordHash: string;
  name: string;
  roles: string[]; // e.g. ['admin', 'staff', 'instructor']
  isActive: boolean;
  lastLogin?: Date;
  createdAt: Date;
  updatedAt: Date;
}

const UserSchema = new Schema<IUser>({
  tenantId: { type: String, required: true, index: true },
  email: { type: String, required: true, unique: true },
  passwordHash: { type: String, required: true },
  name: { type: String, required: true },
  roles: [{ type: String, required: true }],
  isActive: { type: Boolean, default: true },
  lastLogin: Date
}, {
  timestamps: true
});

export const User = model<IUser>('User', UserSchema);
