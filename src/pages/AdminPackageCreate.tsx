"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { City } from '../types';
import { useRouter, useParams } from 'next/navigation';
import { 
  Package as PackageIcon, Save, DollarSign, Calendar, MapPin, 
  ArrowLeft, FileText, CheckCircle, Globe, LayoutList, Image as ImageIcon,
  Map, Camera
} from 'lucide-react';
import { useForm, useFieldArray } from 'react-hook-form';
import { toast } from 'sonner';
import FileUpload from '@/components/admin/FileUpload';
import LoadingButton from '@/components/admin/LoadingButton';
import FormField from '@/components/admin/FormField';
import { showErrorToast } from '@/lib/errorHandler';
import { useAutoSave } from '@/hooks/useAutoSave';
import { useUnsavedChanges } from '@/hooks/useUnsavedChanges';

interface PricingDetailForm {
  pax: string;
  price_per_person: string;
}

interface ItineraryForm {
  day: string;
  title: string;
  description: string;
}

interface PackageFormValues {
  status: string;
  is_featured: boolean;
  sort_order: number;
  pricing_details: PricingDetailForm[];
  itinerary: ItineraryForm[];
  translations: {
    en: Record<string, string>;
    ms: Record<string, string>;
  };
  includes: string;
  excludes: string;
  category: 'tour' | 'outbound';
  slug: string;
  image: string;
  name: string;
  location_tag?: string;
  pre_order_info?: string;
  duration: string;
  city_id?: number | string | null;
  short_description?: string;
  description?: string;
  price?: number | string;
  child_price?: number | string;
  price_display?: string;
  itinerary_text?: string;
  drone_price?: number | string;
  drone_location?: string;
  notes?: string;
  meta_title?: string;
  meta_description?: string;
}

export default function AdminPackageCreate() {
  const params = useParams<{ id?: string }>();
  const id = params?.id;
  const [cities, setCities] = useState<City[]>([]);
  const [activeTab, setActiveTab] = useState('info');
  const [loading, setLoading] = useState(false);
  const router = useRouter();

  const { register, handleSubmit, control, reset, watch, setValue, formState: { errors, isDirty } } = useForm<PackageFormValues>({
    mode: 'onChange', // Enable real-time validation
    defaultValues: {
      status: 'active',
      is_featured: false,
      sort_order: 0,
      pricing_details: [{ pax: '', price_per_person: '' }],
      itinerary: [{ day: '', title: '', description: '' }],
      translations: { en: {}, ms: {} },
      includes: '',
      excludes: '',
      category: 'tour',
      slug: '',
      image: ''
    }
  });

  const { fields: pricingFields, append: appendPricing, remove: removePricing } = useFieldArray({
    control,
    name: "pricing_details"
  });

  const { fields: itineraryFields, append: appendItinerary, remove: removeItinerary } = useFieldArray({
    control,
    name: "itinerary"
  });

  // Auto-save draft
  const formData = watch();
  const { restoreDraft, clearDraft } = useAutoSave({
    data: formData,
    key: `draft-package-${id || 'new'}`,
    enabled: !id, // Only auto-save for new packages
  });

  // Warn before leaving with unsaved changes
  useUnsavedChanges(isDirty);

  // Auto-generate slug from name
  useEffect(() => {
    const subscription = watch((value, { name }) => {
      if (name === 'name' && value.name && !value.slug) {
        const slug = value.name
          .toLowerCase()
          .replace(/ /g, '-')
          .replace(/[^\w-]+/g, '');
        setValue('slug', slug);
      }

      // Auto-fill meta title from name
      if (name === 'name' && value.name && !value.meta_title) {
        setValue('meta_title', `${value.name} - Wonderful Toba`);
      }
    });
    return () => subscription.unsubscribe();
  }, [watch, setValue]);

  useEffect(() => {
    api.get('/cities').then(res => setCities(res.data)).catch(console.error);
    
    if (id) {
      api.get(`/packages/${id}`).then(res => {
        const pkg = res.data;
        reset({
          ...pkg,
          includes: pkg.includes?.join(', ') || '',
          excludes: pkg.excludes?.join(', ') || '',
          image: pkg.images?.join(', ') || '',
        });
      }).catch(err => {
        console.error('Error fetching package:', err);
        showErrorToast(err, toast);
      });
    } else {
      // Try to restore draft for new packages
      const draft = restoreDraft();
      if (draft && draft.name) {
        const shouldRestore = window.confirm(
          'Ditemukan draft yang belum tersimpan. Restore?'
        );
        if (shouldRestore) {
          reset(draft);
          toast.info('Draft berhasil dipulihkan');
        } else {
          clearDraft();
        }
      }
    }
  }, [id, reset, restoreDraft, clearDraft]);

  const onSubmit = async (data: PackageFormValues) => {
    setLoading(true);
    try {
      const includesArray = data.includes ? data.includes.split(',').map((s: string) => s.trim()) : [];
      const excludesArray = data.excludes ? data.excludes.split(',').map((s: string) => s.trim()) : [];
      
      const payload = {
        name: data.name,
        location_tag: data.location_tag,
        pre_order_info: data.pre_order_info,
        duration: data.duration,
        city_id: data.city_id ? Number(data.city_id) : null,
        short_description: data.short_description,
        description: data.description,
        price: Number(data.price || 0),
        child_price: Number(data.child_price || 0),
        price_display: data.price_display,
        pricing_details: data.pricing_details.filter((p: PricingDetailForm) => p.pax),
        itinerary: data.itinerary.filter((i: ItineraryForm) => i.title),
        itinerary_text: data.itinerary_text,
        drone_price: Number(data.drone_price || 0),
        drone_location: data.drone_location,
        includes: includesArray,
        excludes: excludesArray,
        notes: data.notes,
        status: data.status,
        is_featured: data.is_featured,
        is_outbound: data.category === 'outbound',
        sort_order: Number(data.sort_order || 0),
        meta_title: data.meta_title,
        meta_description: data.meta_description,
        translations: data.translations,
        slug: data.slug || data.name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''),
        images: data.image ? data.image.split(',').map((s: string) => s.trim()) : [],
      };

      if (id) {
        await api.put(`/packages/${id}`, payload);
        toast.success('Paket Wisata berhasil diperbarui');
      } else {
        await api.post('/packages', payload);
        toast.success('Paket Wisata baru berhasil ditambahkan');
        clearDraft(); // Clear draft after successful save
      }
      router.push('/admin/packages');
    } catch (error) {
      console.error('Error saving package:', error);
      showErrorToast(error, toast);
    } finally {
      setLoading(false);
    }
  };

  const tabs = [
    { id: 'info', label: 'Identitas Paket', icon: <FileText size={18} /> },
    { id: 'harga', label: 'Harga', icon: <DollarSign size={18} /> },
    { id: 'itinerary', label: 'Itinerary', icon: <Map size={18} /> },
    { id: 'fasilitas', label: 'Fasilitas & Catatan', icon: <CheckCircle size={18} /> },
    { id: 'seo', label: 'Status & SEO', icon: <Globe size={18} /> },
    { id: 'bahasa', label: 'Terjemahan', icon: <LayoutList size={18} /> },
  ];

  return (
    <div className="max-w-6xl mx-auto pb-12">
      <button onClick={() => router.push('/admin/packages')} className="flex items-center gap-2 text-slate-500 hover:text-obaja-blue font-bold mb-6 transition-colors">
        <ArrowLeft size={18} /> Kembali ke Manajemen Paket
      </button>

      <div className="bg-white rounded-[3rem] p-8 md:p-12 shadow-sm border border-slate-100">
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
          <div>
            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight mb-2">
              {id ? 'Edit Paket Wisata' : 'Tambah Paket Wisata'}
            </h2>
            <p className="text-slate-500 font-medium">Buat paket wisata menarik dengan fitur lengkap.</p>
          </div>
          <LoadingButton
            onClick={handleSubmit(onSubmit)}
            loading={loading}
            loadingText={id ? 'Menyimpan...' : 'Membuat...'}
            icon={<Save size={20} />}
          >
            {id ? 'Simpan Perubahan' : 'Simpan Paket Wisata'}
          </LoadingButton>
        </div>

        {/* Tab Navigation */}
        <div className="flex overflow-x-auto hide-scrollbar border-b border-slate-100 mb-8 space-x-2">
          {tabs.map(tab => (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id)}
              className={`flex items-center space-x-2 px-6 py-4 border-b-2 font-bold transition-all whitespace-nowrap ${activeTab === tab.id ? 'border-obaja-blue text-obaja-blue' : 'border-transparent text-slate-400 hover:text-slate-600'}`}
            >
              {tab.icon}
              <span>{tab.label}</span>
            </button>
          ))}
        </div>

        <form className="space-y-8">
          {/* IDENTITAS PAKET */}
          {activeTab === 'info' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <FormField
                  label="Nama Paket"
                  error={errors.name}
                  icon={<PackageIcon size={20} />}
                  required
                >
                  <input
                    {...register('name', { 
                      required: 'Nama paket wajib diisi',
                      minLength: { value: 5, message: 'Nama paket minimal 5 karakter' }
                    })}
                    className={`w-full pl-12 pr-4 py-4 bg-slate-50 border-2 ${
                      errors.name ? 'border-rose-500' : 'border-transparent'
                    } rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900 transition-all`}
                    placeholder="Contoh: Explore Danau Toba 3D2N"
                  />
                </FormField>

                <FormField
                  label="Kategori Layanan"
                  error={errors.category}
                  required
                >
                  <select
                    {...register('category', { required: 'Kategori wajib dipilih' })}
                    className={`w-full px-5 py-4 bg-slate-50 border-2 ${
                      errors.category ? 'border-rose-500' : 'border-transparent'
                    } rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900 appearance-none transition-all`}
                  >
                    <option value="tour">Tour & Travel</option>
                    <option value="outbound">Corporate Outbound</option>
                  </select>
                </FormField>

                <FormField
                  label="Friendly URL (Slug)"
                  error={errors.slug}
                  helperText="Otomatis dibuat dari nama paket"
                  maxLength={100}
                  currentLength={watch('slug')?.length || 0}
                >
                  <input
                    {...register('slug', {
                      maxLength: { value: 100, message: 'Slug maksimal 100 karakter' },
                      pattern: {
                        value: /^[a-z0-9-]+$/,
                        message: 'Slug hanya boleh huruf kecil, angka, dan tanda hubung'
                      }
                    })}
                    className={`w-full px-5 py-4 bg-slate-50 border-2 ${
                      errors.slug ? 'border-rose-500' : 'border-transparent'
                    } rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900 transition-all`}
                    placeholder="Contoh: paket-tour-danau-toba-3d2n"
                  />
                </FormField>

                <FormField
                  label="Durasi"
                  error={errors.duration}
                  icon={<Calendar size={20} />}
                  required
                >
                  <input
                    {...register('duration', { required: 'Durasi wajib diisi' })}
                    className={`w-full pl-12 pr-4 py-4 bg-slate-50 border-2 ${
                      errors.duration ? 'border-rose-500' : 'border-transparent'
                    } rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900 transition-all`}
                    placeholder="3 Hari 2 Malam"
                  />
                </FormField>

                <FormField
                  label="Tag Lokasi (Badge atas foto)"
                  error={errors.location_tag}
                >
                  <input
                    {...register('location_tag')}
                    className="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900 transition-all"
                    placeholder="Contoh: Samosir, Sumatera Utara"
                  />
                </FormField>
              </div>

              <FormField
                label="Pre-order Info (Opsional)"
                error={errors.pre_order_info}
              >
                <input
                  {...register('pre_order_info')}
                  className="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium transition-all"
                  placeholder="Contoh: Booking 7 hari sebelum..."
                />
              </FormField>

              <div className="pt-4 border-t border-slate-100">
                <FormField
                  label="Deskripsi Singkat"
                  error={errors.short_description}
                  maxLength={200}
                  currentLength={watch('short_description')?.length || 0}
                  helperText="Tampil ringkas di bawah harga"
                >
                  <textarea
                    {...register('short_description', {
                      maxLength: { value: 200, message: 'Deskripsi singkat maksimal 200 karakter' }
                    })}
                    rows={2}
                    className={`w-full p-5 bg-slate-50 border-2 ${
                      errors.short_description ? 'border-rose-500' : 'border-transparent'
                    } rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium transition-all`}
                    placeholder="Tampil ringkas di bawah harga..."
                  />
                </FormField>
              </div>

              <FormField
                label="Deskripsi Lengkap"
                error={errors.description}
              >
                <textarea
                  {...register('description')}
                  rows={6}
                  className="w-full p-5 bg-slate-50 border-2 border-transparent rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium transition-all"
                  placeholder="Penjelasan lengkap mengenai perjalanan ini..."
                />
              </FormField>

              <div className="space-y-3">
                <FileUpload 
                  label="Galeri Gambar (Upload)" 
                  currentValue={watch('image')} 
                  onUploadSuccess={(url) => {
                    const current = watch('image');
                    const newVal = current ? `${current}, ${url}` : url;
                    setValue('image', newVal);
                  }} 
                />
                <p className="text-[9px] text-slate-400 mt-1 uppercase font-bold tracking-widest px-1">
                  Atau masukkan URL manual (pisahkan dengan koma):
                </p>
                <div className="relative">
                  <ImageIcon className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                  <textarea
                    {...register('image')}
                    rows={2}
                    className="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium text-slate-900 transition-all"
                    placeholder="https://example.com/tour1.jpg, https://example.com/tour2.jpg"
                  />
                </div>
              </div>
            </div>
          )}

          {/* HARGA */}
          {activeTab === 'harga' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="bg-slate-50 p-6 rounded-2xl space-y-4 mb-6">
                <h4 className="font-bold text-slate-900 border-b border-slate-200 pb-2">Tabel Harga per Orang</h4>
                {pricingFields.map((field, index) => (
                  <div key={field.id} className="flex gap-4">
                    <div className="flex-1">
                      <input
                        {...register(`pricing_details.${index}.pax`)}
                        placeholder="Jumlah Orang (mis. 2)"
                        className="w-full px-5 py-3 bg-white border border-slate-100 rounded-xl focus:ring-2 focus:ring-obaja-blue font-medium"
                      />
                    </div>
                    <div className="flex-1">
                      <input
                        {...register(`pricing_details.${index}.price_per_person`)}
                        placeholder="Harga / Orang (Rp)"
                        className="w-full px-5 py-3 bg-white border border-slate-100 rounded-xl focus:ring-2 focus:ring-obaja-blue font-medium"
                      />
                    </div>
                    <button type="button" onClick={() => removePricing(index)} className="px-4 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors font-bold">Hapus</button>
                  </div>
                ))}
                <button type="button" onClick={() => appendPricing({ pax: '', price_per_person: '' })} className="text-obaja-blue font-bold text-sm bg-blue-50/50 px-6 py-3 rounded-xl hover:bg-blue-100 transition-colors">
                  + Tambah Baris Harga
                </button>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <FormField
                  label="Harga Dasar (Default)"
                  error={errors.price}
                  icon={<DollarSign size={20} />}
                  required
                  helperText="Masukkan angka tanpa titik atau koma"
                >
                  <input
                    type="number"
                    {...register('price', { 
                      required: 'Harga wajib diisi',
                      min: { value: 0, message: 'Harga tidak boleh negatif' }
                    })}
                    className={`w-full pl-12 pr-4 py-4 bg-slate-50 border-2 ${
                      errors.price ? 'border-rose-500' : 'border-transparent'
                    } rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900 transition-all`}
                    placeholder="500000"
                  />
                </FormField>

                <FormField
                  label="Harga Anak (8 Thn Kebawah)"
                  error={errors.child_price}
                  icon={<DollarSign size={20} />}
                  helperText="Opsional, kosongkan jika tidak ada"
                >
                  <input
                    type="number"
                    {...register('child_price', {
                      min: { value: 0, message: 'Harga tidak boleh negatif' }
                    })}
                    className={`w-full pl-12 pr-4 py-4 bg-slate-50 border-2 ${
                      errors.child_price ? 'border-rose-500' : 'border-transparent'
                    } rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900 transition-all`}
                    placeholder="250000"
                  />
                </FormField>
              </div>

              <FormField
                label="Teks Harga Custom"
                error={errors.price_display}
                helperText="Tampil di card paket, contoh: Mulai dari Rp 500.000 / pax"
              >
                <input
                  {...register('price_display')}
                  className="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium transition-all"
                  placeholder="Mulai dari Rp 500.000 / pax"
                />
              </FormField>
            </div>
          )}

          {/* ITINERARY */}
          {activeTab === 'itinerary' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="bg-slate-50 p-6 rounded-2xl space-y-4">
                <h4 className="font-bold text-slate-900 border-b border-slate-200 pb-2">Jadwal Harian</h4>
                {itineraryFields.map((field, index) => (
                  <div key={field.id} className="bg-white p-4 rounded-xl border border-slate-100 flex flex-col gap-3">
                    <div className="flex justify-between items-center">
                      <span className="font-bold text-slate-900">Hari {index + 1}</span>
                      <button type="button" onClick={() => removeItinerary(index)} className="text-rose-500 font-bold text-xs p-2">Hapus Hari</button>
                    </div>
                    <div className="grid grid-cols-4 gap-4">
                      <input
                        {...register(`itinerary.${index}.day`)}
                        placeholder="Hari ke-"
                        className="col-span-1 px-4 py-3 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-obaja-blue font-medium"
                      />
                      <input
                        {...register(`itinerary.${index}.title`)}
                        placeholder="Judul / Destinasi Utama"
                        className="col-span-3 px-4 py-3 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-obaja-blue font-medium"
                      />
                    </div>
                    <textarea
                      {...register(`itinerary.${index}.description`)}
                      placeholder="Jelaskan aktivitas hari ini secara detail..."
                      rows={2}
                      className="w-full px-4 py-3 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-obaja-blue font-medium"
                    />
                  </div>
                ))}
                <button type="button" onClick={() => appendItinerary({ day: '', title: '', description: '' })} className="text-obaja-blue font-bold text-sm bg-blue-50/50 px-6 py-3 rounded-xl hover:bg-blue-100 transition-colors mt-2 text-center w-full">
                  + Tambah Hari
                </button>
              </div>

              <div className="space-y-3 pt-4 border-t border-slate-100">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Atau Isi Itinerary Teks Bebas</label>
                <textarea
                  {...register('itinerary_text')}
                  rows={4}
                  className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium"
                  placeholder="Gunakan kolom ini jika tidak ingin menggunakan format per hari..."
                />
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100 mt-6 md:mt-8!">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1 flex items-center gap-2"><Camera size={14}/> Biaya Tambahan Drone</label>
                  <input
                    type="number"
                    {...register('drone_price')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium"
                    placeholder="1500000"
                  />
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Lokasi Drone</label>
                  <input
                    {...register('drone_location')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium"
                    placeholder="Bukit Holbung, Samosir"
                  />
                </div>
              </div>
            </div>
          )}

          {/* FASILITAS */}
          {activeTab === 'fasilitas' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Sudah Termasuk (pisah koma)</label>
                  <input
                    {...register('includes')}
                    className="w-full px-5 py-4 bg-emerald-50 text-emerald-900 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 font-medium"
                    placeholder="Hotel bintang 4, Makan 3x sehari..."
                  />
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Tidak Termasuk (pisah koma)</label>
                  <input
                    {...register('excludes')}
                    className="w-full px-5 py-4 bg-rose-50 text-rose-900 border-none rounded-2xl focus:ring-2 focus:ring-rose-500 font-medium"
                    placeholder="Tiket pesawat, Tip guide..."
                  />
                </div>
              </div>
              <div className="space-y-3 pt-4 border-t border-slate-100">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Catatan Penting (Notes)</label>
                <textarea
                  {...register('notes')}
                  rows={4}
                  className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium"
                  placeholder="Peserta minimal 2 orang, anak di atas 8 tahun bayar penuh..."
                />
              </div>
            </div>
          )}

          {/* SEO & STATUS */}
          {activeTab === 'seo' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <FormField
                  label="Status Publikasi"
                  error={errors.status}
                  required
                >
                  <select
                    {...register('status', { required: 'Status wajib dipilih' })}
                    className={`w-full px-5 py-4 bg-slate-50 border-2 ${
                      errors.status ? 'border-rose-500' : 'border-transparent'
                    } rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900 appearance-none transition-all`}
                  >
                    <option value="active">Aktif (Tampil di Web)</option>
                    <option value="inactive">Nonaktif (Draft)</option>
                  </select>
                </FormField>

                <FormField
                  label="Urutan Tampil (Sort Order)"
                  error={errors.sort_order}
                  helperText="Angka lebih kecil tampil lebih dulu"
                >
                  <input
                    type="number"
                    {...register('sort_order', {
                      min: { value: 0, message: 'Urutan tidak boleh negatif' }
                    })}
                    className={`w-full px-5 py-4 bg-slate-50 border-2 ${
                      errors.sort_order ? 'border-rose-500' : 'border-transparent'
                    } rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900 transition-all`}
                    placeholder="0"
                  />
                </FormField>
              </div>

              <div className="flex items-center space-x-3 bg-slate-50 p-6 rounded-2xl">
                <input
                  type="checkbox"
                  {...register('is_featured')}
                  id="is_featured"
                  className="w-5 h-5 text-obaja-blue focus:ring-obaja-blue border-slate-300 rounded"
                />
                <label htmlFor="is_featured" className="font-bold text-slate-900">Jadikan Paket Unggulan (Tampil di Home)</label>
              </div>

              <div className="pt-4 border-t border-slate-100 space-y-4">
                <h4 className="font-bold text-slate-900 mb-2">Optimasi Mesin Pencari (SEO)</h4>
                
                <FormField
                  label="Meta Title"
                  error={errors.meta_title}
                  maxLength={60}
                  currentLength={watch('meta_title')?.length || 0}
                  helperText="Idealnya 50-60 karakter untuk tampilan optimal di Google"
                >
                  <input
                    {...register('meta_title', {
                      maxLength: { value: 60, message: 'Meta title maksimal 60 karakter' }
                    })}
                    className={`w-full px-5 py-4 bg-slate-50 border-2 ${
                      errors.meta_title ? 'border-rose-500' : 'border-transparent'
                    } rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium transition-all`}
                    placeholder="Paket Wisata Danau Toba Terbaik..."
                  />
                </FormField>

                <FormField
                  label="Meta Description"
                  error={errors.meta_description}
                  maxLength={160}
                  currentLength={watch('meta_description')?.length || 0}
                  helperText="Idealnya 150-160 karakter untuk tampilan optimal di Google"
                >
                  <textarea
                    {...register('meta_description', {
                      maxLength: { value: 160, message: 'Meta description maksimal 160 karakter' }
                    })}
                    rows={2}
                    className={`w-full p-5 bg-slate-50 border-2 ${
                      errors.meta_description ? 'border-rose-500' : 'border-transparent'
                    } rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium transition-all`}
                    placeholder="Nikmati liburan ke Samosir dengan fasilitas lengkap..."
                  />
                </FormField>
              </div>
            </div>
          )}

          {/* TERJEMAHAN */}
          {activeTab === 'bahasa' && (
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 animate-in fade-in slide-in-from-bottom-4">
              <div className="space-y-6 bg-slate-50 p-6 rounded-3xl">
                <div className="flex items-center space-x-2 border-b border-slate-200 pb-4">
                  <span className="text-2xl">🇺🇸</span>
                  <h3 className="font-bold text-slate-900">English (EN)</h3>
                </div>
                <div className="space-y-4">
                  <input
                    {...register('translations.en.name')}
                    className="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Package Name (EN)"
                  />
                  <textarea
                    {...register('translations.en.short_description')}
                    rows={2}
                    className="w-full p-4 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Short Description (EN)"
                  />
                  <textarea
                    {...register('translations.en.notes')}
                    rows={2}
                    className="w-full p-4 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Notes (EN)"
                  />
                </div>
              </div>

              <div className="space-y-6 bg-slate-50 p-6 rounded-3xl">
                <div className="flex items-center space-x-2 border-b border-slate-200 pb-4">
                  <span className="text-2xl">🇲🇾</span>
                  <h3 className="font-bold text-slate-900">Malaysia (MS)</h3>
                </div>
                <div className="space-y-4">
                  <input
                    {...register('translations.ms.name')}
                    className="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Nama Pakej (MS)"
                  />
                  <textarea
                    {...register('translations.ms.short_description')}
                    rows={2}
                    className="w-full p-4 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Penerangan Ringkas (MS)"
                  />
                  <textarea
                    {...register('translations.ms.notes')}
                    rows={2}
                    className="w-full p-4 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Nota (MS)"
                  />
                </div>
              </div>
            </div>
          )}
        </form>
      </div>
    </div>
  );
}
