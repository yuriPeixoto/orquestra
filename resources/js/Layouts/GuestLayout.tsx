import { PropsWithChildren } from 'react';

export default function GuestLayout({ children }: PropsWithChildren) {
    return (
        <div className="flex min-h-screen">
            {/* Left — branding panel */}
            <div className="hidden lg:flex lg:w-1/2 flex-col justify-between bg-[#0F172A] p-12">
                <div>
                    <span className="text-white text-2xl font-bold tracking-tight">
                        Orquestra
                    </span>
                </div>

                <div>
                    <blockquote className="space-y-4">
                        <p className="text-[#94A3B8] text-lg leading-relaxed">
                            "Governance over improvisation.
                            <br />
                            Clarity over complexity.
                            <br />
                            Structure over volume."
                        </p>
                        <footer className="text-[#475569] text-sm">
                            Technical Operations & Governance Platform
                        </footer>
                    </blockquote>
                </div>

                <div className="grid grid-cols-3 gap-4 opacity-30">
                    <div className="h-1 rounded-full bg-[#0369A1]" />
                    <div className="h-1 rounded-full bg-[#334155]" />
                    <div className="h-1 rounded-full bg-[#0369A1]" />
                </div>
            </div>

            {/* Right — form panel */}
            <div className="flex flex-1 flex-col items-center justify-center bg-[#F8FAFC] px-6 py-12 lg:px-16">
                <div className="w-full max-w-sm">
                    {/* Mobile logo */}
                    <div className="mb-8 lg:hidden">
                        <span className="text-[#0F172A] text-2xl font-bold tracking-tight">
                            Orquestra
                        </span>
                    </div>

                    {children}
                </div>
            </div>
        </div>
    );
}
