"use client";

import React, { useState, useEffect } from "react";
import { ArrowLeft, Save, Layout, Image, Shield, MessageSquare, Loader2 } from "lucide-react";
import { useRouter } from "next/navigation";
import { toast } from "sonner";
import { cn } from "@/utils/cn";

type CMSSection = {
  id: string;
  title: string;
  subtitle?: string;
  content?: string;
  imageUrl?: string;
  items?: Array<any>;
};

type CMSData = {
  hero: CMSSection;
  slider: CMSSection & { items?: Array<{ image: string; caption?: string }> };
  features: CMSSection & { items?: Array<{ icon: string; title: string; description: string }> };
  testimonials: CMSSection & { items?: Array<{ name: string; role: string; text: string; rating: number }> };
};

export default function AdminLandingPage({ category }: { category: "tour" | "outbound" }) {
  const router = useRouter();
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [activeTab, setActiveTab] = useState("hero");
  const [cmsData, setCmsData] = useState<CMSData>({
    hero: {
      id: "hero",
      title: "",
      subtitle: "",
      imageUrl: "",
    },
    slider: {
      id: "slider",
      title: "",
      items: [],
    },
    features: {
      id: "features",
      title: "",
      items: [],
    },
    testimonials: {
      id: "testimonials",
      title: "",
      items: [],
    },
  });

  useEffect(() => {
    fetchCMSData();
  }, [category]);

  const fetchCMSData = async () => {
    setLoading(true);
    try {
      const res = await fetch(`/api/settings/landing-${category}`);
      if (res.ok) {
        const data = await res.json();
        setCmsData(data);
      }
    } catch (error) {
      console.error("Error fetching CMS data:", error);
      toast.error("Gagal memuat data CMS");
    } finally {
      setLoading(false);
    }
  };

  const handleSave = async () => {
    setSaving(true);
    try {
      const res = await fetch(`/api/settings/landing-${category}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(cmsData),
      });

      if (res.ok) {
        toast.success("Perubahan berhasil disimpan!");
      } else {
        toast.error("Gagal menyimpan perubahan");
      }
    } catch (error) {
      console.error("Error saving CMS data:", error);
      toast.error("Terjadi kesalahan saat menyimpan");
    } finally {
      setSaving(false);
    }
  };

  const updateSection = (section: keyof CMSData, field: string, value: any) => {
    setCmsData((prev) => ({
      ...prev,
      [section]: {
        ...prev[section],
        [field]: value,
      },
    }));
  };

  const tabs = [
    { id: "hero", label: "Hero Section", icon: Layout },
    { id: "slider", label: "Slider Beranda", icon: Image },
    { id: "features", label: category === "tour" ? "Mengapa Kami" : "Layanan", icon: Shield },
    { id: "testimonials", label: "Testimoni", icon: MessageSquare },
  ];

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="text-center">
          <Loader2 className="w-12 h-12 animate-spin text-toba-green mx-auto mb-4" />
          <p className="text-slate-400 font-bold uppercase text-xs tracking-widest">Memuat CMS...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="p-6 lg:p-10">
      <div className="max-w-6xl mx-auto pb-20">
        {/* Header */}
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
          <div>
            <button
              onClick={() => router.push("/admin")}
              className="flex items-center gap-2 text-slate-400 hover:text-slate-900 transition-colors font-bold mb-4 group"
            >
              <ArrowLeft className="w-[18px] h-[18px] group-hover:-translate-x-1 transition-transform" />
              Kembali
            </button>
            <h2 className="text-4xl font-black text-slate-900 tracking-tight">
              {category === "tour" ? "Beranda" : "Outbound"} <span className="text-toba-green">CMS</span>
            </h2>
            <p className="text-slate-500 font-medium">
              Kelola konten {category === "tour" ? "website Tour & Travel" : "landing page Corporate Outbound"}.
            </p>
          </div>
          <button
            onClick={handleSave}
            disabled={saving}
            className="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black hover:bg-slate-800 transition-all shadow-xl shadow-slate-200 flex items-center space-x-2 disabled:opacity-50"
          >
            {saving ? (
              <>
                <Loader2 className="w-[18px] h-[18px] animate-spin" />
                <span>Menyimpan...</span>
              </>
            ) : (
              <>
                <Save className="w-[18px] h-[18px]" />
                <span>Simpan Perubahan</span>
              </>
            )}
          </button>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
          {/* Sidebar Tabs */}
          <div className="lg:col-span-3">
            <div className="bg-white rounded-[2rem] p-3 shadow-sm border border-slate-100 sticky top-24">
              {tabs.map((tab) => (
                <button
                  key={tab.id}
                  onClick={() => setActiveTab(tab.id)}
                  className={cn(
                    "w-full flex items-center gap-4 px-5 py-4 rounded-xl font-bold transition-all mb-1",
                    activeTab === tab.id
                      ? "bg-toba-green text-white shadow-lg shadow-emerald-100 scale-[1.02]"
                      : "text-slate-400 hover:text-slate-600 hover:bg-slate-50"
                  )}
                >
                  <tab.icon className="w-5 h-5" />
                  <span>{tab.label}</span>
                </button>
              ))}
            </div>
          </div>

          {/* Editor Area */}
          <div className="lg:col-span-9 bg-white rounded-[2.5rem] p-8 md:p-12 shadow-sm border border-slate-100 min-h-[600px]">
            {activeTab === "hero" && (
              <div className="space-y-8">
                <div className="flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
                  <div className="w-14 h-14 bg-slate-900 text-white rounded-[1.25rem] flex items-center justify-center shadow-xl shadow-slate-200">
                    <Layout className="w-6 h-6" />
                  </div>
                  <div>
                    <h3 className="text-2xl font-black text-slate-900 tracking-tight leading-none mb-2">Hero Utama</h3>
                    <p className="text-slate-400 font-medium text-sm">
                      Ubah tampilan utama saat website pertama kali dibuka.
                    </p>
                  </div>
                </div>

                <div className="space-y-6">
                  <div className="space-y-2">
                    <label className="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">
                      Judul Hero
                    </label>
                    <input
                      type="text"
                      value={cmsData.hero.title}
                      onChange={(e) => updateSection("hero", "title", e.target.value)}
                      className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900 placeholder:font-medium transition-all"
                      placeholder="Masukkan judul hero..."
                    />
                  </div>
                  <div className="space-y-2">
                    <label className="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Sub-Judul</label>
                    <input
                      type="text"
                      value={cmsData.hero.subtitle}
                      onChange={(e) => updateSection("hero", "subtitle", e.target.value)}
                      className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900 placeholder:font-medium transition-all"
                      placeholder="Masukkan sub-judul..."
                    />
                  </div>
                  <div className="space-y-2">
                    <label className="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">
                      URL Gambar Hero
                    </label>
                    <input
                      type="text"
                      value={cmsData.hero.imageUrl}
                      onChange={(e) => updateSection("hero", "imageUrl", e.target.value)}
                      className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900 placeholder:font-medium transition-all"
                      placeholder="/assets/images/hero.jpg"
                    />
                  </div>
                  {cmsData.hero.imageUrl && (
                    <div className="mt-4 rounded-2xl overflow-hidden border border-slate-100">
                      <img
                        src={cmsData.hero.imageUrl}
                        alt="Preview"
                        className="w-full h-48 object-cover"
                        onError={(e) => {
                          e.currentTarget.src = "https://via.placeholder.com/800x400?text=Image+Not+Found";
                        }}
                      />
                    </div>
                  )}
                </div>
              </div>
            )}

            {activeTab === "slider" && (
              <div className="space-y-8">
                <div className="flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
                  <div className="w-14 h-14 bg-slate-900 text-white rounded-[1.25rem] flex items-center justify-center shadow-xl shadow-slate-200">
                    <Image className="w-6 h-6" />
                  </div>
                  <div>
                    <h3 className="text-2xl font-black text-slate-900 tracking-tight leading-none mb-2">
                      Slider Beranda
                    </h3>
                    <p className="text-slate-400 font-medium text-sm">Kelola gambar slider di halaman utama.</p>
                  </div>
                </div>

                <div className="space-y-6">
                  {(cmsData.slider.items || []).map((item: any, index: number) => (
                    <div key={index} className="bg-slate-50 rounded-2xl p-6 space-y-4">
                      <div className="flex justify-between items-center">
                        <p className="font-black text-slate-900">Slide {index + 1}</p>
                        <button
                          onClick={() => {
                            const newItems = [...(cmsData.slider.items || [])];
                            newItems.splice(index, 1);
                            updateSection('slider', 'items', newItems);
                          }}
                          className="text-red-500 hover:text-red-700 text-sm font-bold"
                        >
                          Hapus
                        </button>
                      </div>
                      <input
                        type="text"
                        value={item.image || ''}
                        onChange={(e) => {
                          const newItems = [...(cmsData.slider.items || [])];
                          newItems[index] = { ...newItems[index], image: e.target.value };
                          updateSection('slider', 'items', newItems);
                        }}
                        placeholder="URL Gambar"
                        className="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl font-bold"
                      />
                      <input
                        type="text"
                        value={item.caption || ''}
                        onChange={(e) => {
                          const newItems = [...(cmsData.slider.items || [])];
                          newItems[index] = { ...newItems[index], caption: e.target.value };
                          updateSection('slider', 'items', newItems);
                        }}
                        placeholder="Caption (optional)"
                        className="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl font-bold"
                      />
                    </div>
                  ))}

                  <button
                    onClick={() => {
                      const newItems = [...(cmsData.slider.items || []), { image: '', caption: '' }];
                      updateSection('slider', 'items', newItems);
                    }}
                    className="w-full py-4 bg-slate-100 text-slate-600 font-black text-sm uppercase tracking-widest rounded-2xl hover:bg-toba-green hover:text-white transition-all"
                  >
                    + Tambah Slide
                  </button>
                </div>
              </div>
            )}

            {activeTab === "features" && (
              <div className="space-y-8">
                <div className="flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
                  <div className="w-14 h-14 bg-slate-900 text-white rounded-[1.25rem] flex items-center justify-center shadow-xl shadow-slate-200">
                    <Shield className="w-6 h-6" />
                  </div>
                  <div>
                    <h3 className="text-2xl font-black text-slate-900 tracking-tight leading-none mb-2">
                      {category === "tour" ? "Mengapa Kami" : "Layanan Kami"}
                    </h3>
                    <p className="text-slate-400 font-medium text-sm">Kelola fitur unggulan dan keunggulan.</p>
                  </div>
                </div>

                <div className="space-y-6">
                  {(cmsData.features.items || []).map((item: any, index: number) => (
                    <div key={index} className="bg-slate-50 rounded-2xl p-6 space-y-4">
                      <div className="flex justify-between items-center">
                        <p className="font-black text-slate-900">Feature {index + 1}</p>
                        <button
                          onClick={() => {
                            const newItems = [...(cmsData.features.items || [])];
                            newItems.splice(index, 1);
                            updateSection('features', 'items', newItems);
                          }}
                          className="text-red-500 hover:text-red-700 text-sm font-bold"
                        >
                          Hapus
                        </button>
                      </div>
                      <input
                        type="text"
                        value={item.icon || ''}
                        onChange={(e) => {
                          const newItems = [...(cmsData.features.items || [])];
                          newItems[index] = { ...newItems[index], icon: e.target.value };
                          updateSection('features', 'items', newItems);
                        }}
                        placeholder="Icon (e.g., Shield, Star, Award)"
                        className="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl font-bold"
                      />
                      <input
                        type="text"
                        value={item.title || ''}
                        onChange={(e) => {
                          const newItems = [...(cmsData.features.items || [])];
                          newItems[index] = { ...newItems[index], title: e.target.value };
                          updateSection('features', 'items', newItems);
                        }}
                        placeholder="Judul"
                        className="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl font-bold"
                      />
                      <textarea
                        value={item.description || ''}
                        onChange={(e) => {
                          const newItems = [...(cmsData.features.items || [])];
                          newItems[index] = { ...newItems[index], description: e.target.value };
                          updateSection('features', 'items', newItems);
                        }}
                        placeholder="Deskripsi"
                        rows={3}
                        className="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl font-medium"
                      />
                    </div>
                  ))}

                  <button
                    onClick={() => {
                      const newItems = [...(cmsData.features.items || []), { icon: '', title: '', description: '' }];
                      updateSection('features', 'items', newItems);
                    }}
                    className="w-full py-4 bg-slate-100 text-slate-600 font-black text-sm uppercase tracking-widest rounded-2xl hover:bg-toba-green hover:text-white transition-all"
                  >
                    + Tambah Feature
                  </button>
                </div>
              </div>
            )}

            {activeTab === "testimonials" && (
              <div className="space-y-8">
                <div className="flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
                  <div className="w-14 h-14 bg-slate-900 text-white rounded-[1.25rem] flex items-center justify-center shadow-xl shadow-slate-200">
                    <MessageSquare className="w-6 h-6" />
                  </div>
                  <div>
                    <h3 className="text-2xl font-black text-slate-900 tracking-tight leading-none mb-2">Testimoni</h3>
                    <p className="text-slate-400 font-medium text-sm">Kelola testimoni pelanggan.</p>
                  </div>
                </div>

                <div className="space-y-6">
                  {(cmsData.testimonials.items || []).map((item: any, index: number) => (
                    <div key={index} className="bg-slate-50 rounded-2xl p-6 space-y-4">
                      <div className="flex justify-between items-center">
                        <p className="font-black text-slate-900">Testimoni {index + 1}</p>
                        <button
                          onClick={() => {
                            const newItems = [...(cmsData.testimonials.items || [])];
                            newItems.splice(index, 1);
                            updateSection('testimonials', 'items', newItems);
                          }}
                          className="text-red-500 hover:text-red-700 text-sm font-bold"
                        >
                          Hapus
                        </button>
                      </div>
                      <div className="grid grid-cols-2 gap-4">
                        <input
                          type="text"
                          value={item.name || ''}
                          onChange={(e) => {
                            const newItems = [...(cmsData.testimonials.items || [])];
                            newItems[index] = { ...newItems[index], name: e.target.value };
                            updateSection('testimonials', 'items', newItems);
                          }}
                          placeholder="Nama"
                          className="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl font-bold"
                        />
                        <input
                          type="text"
                          value={item.role || ''}
                          onChange={(e) => {
                            const newItems = [...(cmsData.testimonials.items || [])];
                            newItems[index] = { ...newItems[index], role: e.target.value };
                            updateSection('testimonials', 'items', newItems);
                          }}
                          placeholder="Jabatan/Perusahaan"
                          className="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl font-bold"
                        />
                      </div>
                      <textarea
                        value={item.text || ''}
                        onChange={(e) => {
                          const newItems = [...(cmsData.testimonials.items || [])];
                          newItems[index] = { ...newItems[index], text: e.target.value };
                          updateSection('testimonials', 'items', newItems);
                        }}
                        placeholder="Testimoni"
                        rows={3}
                        className="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl font-medium"
                      />
                      <div className="flex items-center gap-4">
                        <label className="text-sm font-bold text-slate-600">Rating:</label>
                        <select
                          value={item.rating || 5}
                          onChange={(e) => {
                            const newItems = [...(cmsData.testimonials.items || [])];
                            newItems[index] = { ...newItems[index], rating: parseInt(e.target.value) };
                            updateSection('testimonials', 'items', newItems);
                          }}
                          className="px-4 py-2 bg-white border border-slate-200 rounded-xl font-bold"
                        >
                          <option value={5}>⭐⭐⭐⭐⭐ (5)</option>
                          <option value={4}>⭐⭐⭐⭐ (4)</option>
                          <option value={3}>⭐⭐⭐ (3)</option>
                          <option value={2}>⭐⭐ (2)</option>
                          <option value={1}>⭐ (1)</option>
                        </select>
                      </div>
                    </div>
                  ))}

                  <button
                    onClick={() => {
                      const newItems = [...(cmsData.testimonials.items || []), { name: '', role: '', text: '', rating: 5 }];
                      updateSection('testimonials', 'items', newItems);
                    }}
                    className="w-full py-4 bg-slate-100 text-slate-600 font-black text-sm uppercase tracking-widest rounded-2xl hover:bg-toba-green hover:text-white transition-all"
                  >
                    + Tambah Testimoni
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
