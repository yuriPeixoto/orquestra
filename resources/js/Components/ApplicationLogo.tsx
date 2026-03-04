import logo from '@/assets/images/logo.png';
import { ImgHTMLAttributes } from 'react';

export default function ApplicationLogo({ className, ...props }: ImgHTMLAttributes<HTMLImageElement>) {
    return <img src={logo} alt="Orquestra" className={className} {...props} />;
}
